<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once __DIR__ . '/lib.php';

const PD_UA = 'PianoDepotClone/1.0';

function pd_http_get(string $url): ?string
{
    $tmp = tempnam(sys_get_temp_dir(), 'pdget');
    if ($tmp === false) {
        return null;
    }
    $cmd = sprintf(
        'curl -sL --max-time 90 -A %s -o %s -w "%%{http_code}" %s',
        escapeshellarg(PD_UA),
        escapeshellarg($tmp),
        escapeshellarg($url)
    );
    $code = '000';
    for ($i = 0; $i < 3; $i++) {
        $code = trim(shell_exec($cmd) ?? '000');
        if ($code === '200' && is_file($tmp) && filesize($tmp) > 0) {
            $body = file_get_contents($tmp);
            @unlink($tmp);
            return $body === false ? null : $body;
        }
        usleep(500000);
    }
    fwrite(STDERR, "WARN: failed {$url} (HTTP {$code})\n");
    @unlink($tmp);
    return null;
}

function pd_write_file(string $path, string $contents): void
{
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create {$dir}");
    }
    file_put_contents($path, $contents);
}

function pd_is_wp_asset_url(string $url): bool
{
    $path = parse_url($url, PHP_URL_PATH) ?? '';
    return str_starts_with($path, '/wp-content/') || str_starts_with($path, '/wp-includes/');
}

function pd_sitemap_locs(string $xml): array
{
    preg_match_all('#<loc>\s*(.*?)\s*</loc>#i', $xml, $m);
    return $m[1] ?? [];
}

$sitemaps = [
    'https://pianodepot.com/page-sitemap.xml',
    'https://pianodepot.com/product-sitemap.xml',
];

$pages = ['https://pianodepot.com/' => true];
foreach ($sitemaps as $sm) {
    echo "Fetching sitemap {$sm}\n";
    $xml = pd_http_get($sm);
    if ($xml === null) {
        fwrite(STDERR, "Could not fetch {$sm}\n");
        exit(1);
    }
    foreach (pd_sitemap_locs($xml) as $loc) {
        $path = parse_url($loc, PHP_URL_PATH) ?? '/';
        if (pd_should_clone($path)) {
            $pages[pd_strip_query_fragment($loc)] = true;
        }
    }
}

$urls = array_keys($pages);
sort($urls);

$lines = [
    '# Cloned URLs',
    '',
    'Serve from the project root:',
    '',
    '    php -S localhost:8003 router.php',
    '',
    'Then open http://localhost:8003/',
    '',
];
foreach ($urls as $url) {
    $path = parse_url($url, PHP_URL_PATH) ?? '/';
    if ($path !== '/' && !str_ends_with($path, '/')) {
        $path .= '/';
    }
    $lines[] = '- ' . $path;
}
file_put_contents(PD_ROOT . '/PAGES.md', implode("\n", $lines) . "\n");
echo 'PAGES.md written (' . count($urls) . " pages)\n";

$rawRoot = PD_ROOT . '/scrape/raw';
$pageCount = 0;
$assetQueue = [];
$assetSeen = [];

foreach ($urls as $url) {
    $rawPath = pd_url_to_local_path($url, $rawRoot);
    echo "Page {$url}\n";
    $html = pd_http_get($url);
    if ($html === null) {
        continue;
    }
    pd_write_file($rawPath, $html);
    $pageCount++;
    foreach (pd_extract_local_urls($html, $url) as $asset) {
        if (pd_is_wp_asset_url($asset) && !isset($assetSeen[$asset])) {
            $assetSeen[$asset] = true;
            $assetQueue[] = $asset;
        }
    }
    usleep(500000);
}

$assetCount = 0;
while ($assetQueue) {
    $asset = array_shift($assetQueue);
    $local = pd_url_to_local_path($asset, PD_ROOT);
    if ($local === null) {
        continue;
    }
    if (is_file($local) && filesize($local) > 0) {
        $assetCount++;
        if (str_ends_with(strtolower($local), '.css')) {
            $css = file_get_contents($local);
            if ($css !== false) {
                foreach (pd_extract_local_urls($css, $asset) as $next) {
                    if (pd_is_wp_asset_url($next) && !isset($assetSeen[$next])) {
                        $assetSeen[$next] = true;
                        $assetQueue[] = $next;
                    }
                }
            }
        }
        continue;
    }
    echo "Asset {$asset}\n";
    $body = pd_http_get($asset);
    if ($body === null) {
        continue;
    }
    pd_write_file($local, $body);
    $assetCount++;
    if (str_ends_with(strtolower($local), '.css')) {
        foreach (pd_extract_local_urls($body, $asset) as $next) {
            if (pd_is_wp_asset_url($next) && !isset($assetSeen[$next])) {
                $assetSeen[$next] = true;
                $assetQueue[] = $next;
            }
        }
    }
    usleep(200000);
}

$skipped = count($pages) - $pageCount;
echo "Summary: {$pageCount} pages, {$assetCount} assets, {$skipped} skipped pages\n";
