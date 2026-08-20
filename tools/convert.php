<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/partials/form.php';
require_once __DIR__ . '/lib.php';

function pd_pages_from_markdown(string $file): array
{
    $paths = [];
    foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        if (preg_match('/^- (\S+)/', $line, $m)) {
            $paths[] = $m[1];
        }
    }
    return $paths;
}

function pd_raw_path_for(string $urlPath): string
{
    if ($urlPath === '/') {
        return PD_ROOT . '/scrape/raw/index.html';
    }
    $urlPath = rtrim($urlPath, '/') . '/';
    return PD_ROOT . '/scrape/raw' . $urlPath . 'index.html';
}

function pd_out_path_for(string $urlPath): string
{
    if ($urlPath === '/') {
        return PD_ROOT . '/index.php';
    }
    return PD_ROOT . rtrim($urlPath, '/') . '/index.php';
}

function pd_convert_one(string $urlPath, bool $writeChrome): void
{
    $raw = pd_raw_path_for($urlPath);
    if (!is_file($raw)) {
        fwrite(STDERR, "WARN: missing raw HTML for {$urlPath}\n");
        return;
    }
    $html = file_get_contents($raw);
    if ($html === false) {
        fwrite(STDERR, "WARN: cannot read {$raw}\n");
        return;
    }
    $parts = pd_split_oceanwp($html);
    if (str_starts_with($urlPath, '/product/')) {
        $parts['main'] = pd_replace_add_to_cart($parts['main'], pd_config());
    }
    if (in_array($urlPath, ['/contact-us/', '/contact-us', '/piano-moving-form/', '/piano-moving-form', '/apply-for-credit-at-pianodepot-com/', '/apply-for-credit-at-pianodepot-com'], true)) {
        $parts['main'] = pd_wire_form($parts['main'], $urlPath);
    }
    if ($writeChrome) {
        file_put_contents(PD_ROOT . '/partials/header.php', pd_build_header_php($parts));
        file_put_contents(PD_ROOT . '/partials/footer.php', pd_build_footer_php($parts));
        echo "Wrote partials/header.php and partials/footer.php\n";
    }
    $out = pd_out_path_for($urlPath);
    $dir = dirname($out);
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create {$dir}");
    }
    file_put_contents($out, pd_page_php($parts));
    echo "Wrote {$out}\n";
}

$args = array_slice($argv, 1);
$writeChrome = false;
$filtered = [];
foreach ($args as $arg) {
    if ($arg === '--write-chrome') {
        $writeChrome = true;
        continue;
    }
    $filtered[] = $arg;
}

$targets = $filtered;
if ($targets === []) {
    $targets = pd_pages_from_markdown(PD_ROOT . '/PAGES.md');
}

if (in_array('/', $targets, true) || in_array('', $targets, true)) {
    $writeChrome = true;
}

foreach ($targets as $path) {
    if ($path === '') {
        $path = '/';
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    $thisChrome = $writeChrome && ($path === '/' || count($targets) === 1);
    pd_convert_one($path, $thisChrome && $path === '/');
    if ($thisChrome && $path === '/') {
        $writeChrome = false;
    }
}
