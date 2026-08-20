<?php

function pd_excluded_path_prefixes(): array
{
    return [
        '/cart',
        '/checkout',
        '/account',
        '/shop',
        '/home',
        '/home-11-1-23-holiday-promotion',
    ];
}

function pd_normalize_path(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return '/';
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    if ($path !== '/' && !str_ends_with($path, '/')) {
        $path .= '/';
    }
    return $path;
}

function pd_should_clone(string $path): bool
{
    $path = pd_normalize_path($path);
    if ($path === '/') {
        return true;
    }

    $trimmed = rtrim($path, '/');
    foreach (pd_excluded_path_prefixes() as $prefix) {
        if ($trimmed === $prefix || str_starts_with($trimmed, $prefix . '/')) {
            return false;
        }
    }

    $segment = basename($trimmed);
    if (preg_match('/.+-2$/', $segment)) {
        return false;
    }

    return true;
}

function pd_is_our_host(string $host): bool
{
    $host = strtolower($host);
    return in_array($host, ['pianodepot.com', 'www.pianodepot.com', 'pianodepot.us', 'www.pianodepot.us'], true);
}

function pd_url_to_local_path(string $url, string $root): ?string
{
    $parts = parse_url($url);
    if ($parts === false || empty($parts['host'])) {
        return null;
    }
    if (!pd_is_our_host($parts['host'])) {
        return null;
    }

    $path = $parts['path'] ?? '/';
    if (str_starts_with($path, '/wp-admin/') || $path === '/wp-admin' || str_starts_with($path, '/wp-json/')) {
        return null;
    }

    $root = rtrim($root, '/');
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $assetExts = [
        'css', 'js', 'map', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'otf', 'mp3', 'mp4', 'webm', 'pdf',
        'json', 'xml', 'txt', 'bmp',
    ];

    if ($path === '/' || $path === '') {
        return $root . '/index.html';
    }

    if ($ext !== '' && in_array($ext, $assetExts, true)) {
        return $root . $path;
    }

    $dir = rtrim($path, '/');
    return $root . $dir . '/index.html';
}

function pd_rewrite_html(string $html): string
{
    $hosts = [
        'https://www.pianodepot.com',
        'http://www.pianodepot.com',
        'https://pianodepot.com',
        'http://pianodepot.com',
        'https://www.pianodepot.us',
        'http://www.pianodepot.us',
        'https://pianodepot.us',
        'http://pianodepot.us',
        '//www.pianodepot.com',
        '//pianodepot.com',
        '//www.pianodepot.us',
        '//pianodepot.us',
    ];
    return str_replace($hosts, '', $html);
}

function pd_strip_query_fragment(string $url): string
{
    $hash = strpos($url, '#');
    if ($hash !== false) {
        $url = substr($url, 0, $hash);
    }
    $q = strpos($url, '?');
    if ($q !== false) {
        $url = substr($url, 0, $q);
    }
    return $url;
}

function pd_resolve_url(string $url, string $base): ?string
{
    $url = trim(html_entity_decode($url, ENT_QUOTES | ENT_HTML5));
    $url = trim($url, " \t\n\r\0\x0B\"'");
    if ($url === '' || str_starts_with($url, 'data:') || str_starts_with($url, 'javascript:') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:') || str_starts_with($url, '#')) {
        return null;
    }
    if (str_starts_with($url, '//')) {
        $url = 'https:' . $url;
    }
    if (preg_match('#^https?://#i', $url)) {
        return pd_strip_query_fragment($url);
    }

    $baseParts = parse_url($base);
    if ($baseParts === false || empty($baseParts['scheme']) || empty($baseParts['host'])) {
        return null;
    }
    $origin = $baseParts['scheme'] . '://' . $baseParts['host'] . (isset($baseParts['port']) ? ':' . $baseParts['port'] : '');

    $pathPart = pd_strip_query_fragment($url);
    if (str_starts_with($pathPart, '/')) {
        return $origin . $pathPart;
    }

    $basePath = $baseParts['path'] ?? '/';
    if (!str_ends_with($basePath, '/')) {
        $dir = dirname($basePath);
        $basePath = ($dir === '/' || $dir === '\\' || $dir === '.') ? '/' : $dir . '/';
    }

    $combined = $basePath . $pathPart;
    $segments = [];
    foreach (explode('/', $combined) as $i => $seg) {
        if ($seg === '' || $seg === '.') {
            if ($i === 0) {
                $segments[] = '';
            }
            continue;
        }
        if ($seg === '..') {
            if (count($segments) > 1) {
                array_pop($segments);
            }
            continue;
        }
        $segments[] = $seg;
    }
    $norm = implode('/', $segments);
    if ($norm === '' || $norm[0] !== '/') {
        $norm = '/' . ltrim($norm, '/');
    }
    return $origin . $norm;
}

function pd_extract_local_urls(string $htmlOrCss, string $baseUrl): array
{
    $candidates = [];

    if (preg_match_all('/\b(?:href|src|poster)\s*=\s*(["\'])(.*?)\1/i', $htmlOrCss, $m)) {
        foreach ($m[2] as $raw) {
            $candidates[] = $raw;
        }
    }
    if (preg_match_all('/\bsrcset\s*=\s*(["\'])(.*?)\1/i', $htmlOrCss, $m)) {
        foreach ($m[2] as $srcset) {
            foreach (preg_split('/\s*,\s*/', $srcset) as $item) {
                $url = trim(explode(' ', trim($item))[0]);
                if ($url !== '') {
                    $candidates[] = $url;
                }
            }
        }
    }
    if (preg_match_all('/url\(\s*(["\']?)(.*?)\1\s*\)/i', $htmlOrCss, $m)) {
        foreach ($m[2] as $raw) {
            $candidates[] = $raw;
        }
    }

    $out = [];
    foreach ($candidates as $raw) {
        $abs = pd_resolve_url($raw, $baseUrl);
        if ($abs === null) {
            continue;
        }
        if (pd_url_to_local_path($abs, '/tmp') === null) {
            continue;
        }
        $out[$abs] = true;
    }
    return array_keys($out);
}

function pd_is_shared_stylesheet(string $href): bool
{
    $path = parse_url($href, PHP_URL_PATH) ?? $href;
    return !preg_match('#/wp-content/uploads/elementor/css/post-\d+\.css$#', $path);
}

function pd_load_dom(string $html): DOMDocument
{
    $dom = new DOMDocument();
    $prev = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8">' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
    libxml_use_internal_errors($prev);
    return $dom;
}

function pd_outer_html(DOMNode $node): string
{
    if ($node instanceof DOMDocument) {
        return $node->saveHTML();
    }
    return $node->ownerDocument->saveHTML($node);
}

function pd_open_tag(DOMElement $el): string
{
    $attrs = '';
    foreach ($el->attributes as $a) {
        $attrs .= ' ' . $a->name . '="' . htmlspecialchars($a->value, ENT_QUOTES) . '"';
    }
    return '<' . $el->tagName . $attrs . '>';
}

function pd_href_path(string $href): string
{
    $href = pd_rewrite_html($href);
    $path = parse_url($href, PHP_URL_PATH);
    if (is_string($path) && $path !== '') {
        return $path;
    }
    return $href;
}

function pd_strip_commerce_node(DOMNode $root): void
{
    $xpath = new DOMXPath($root instanceof DOMDocument ? $root : $root->ownerDocument);
    $scope = $root instanceof DOMDocument ? null : $root;
    $query = './/a';
    $links = $scope ? $xpath->query($query, $scope) : $xpath->query('//a');
    $remove = [];
    if ($links) {
        foreach ($links as $a) {
            /** @var DOMElement $a */
            $href = $a->getAttribute('href');
            $class = $a->getAttribute('class');
            $hit = str_contains($href, '/account/')
                || str_contains($href, '/cart/')
                || str_contains($class, 'wcmenucart')
                || str_contains($class, 'wc-forward');
            if (!$hit) {
                continue;
            }
            $node = $a;
            if ($a->parentNode && strtolower($a->parentNode->nodeName) === 'li') {
                $node = $a->parentNode;
            }
            $remove[] = $node;
        }
    }
    $widgets = $scope
        ? $xpath->query('.//*[contains(@class,"woo-mini-cart") or contains(@class,"oceanwp-mini-cart") or contains(@class,"wcmenucart-details")]', $scope)
        : $xpath->query('//*[contains(@class,"woo-mini-cart") or contains(@class,"oceanwp-mini-cart")]');
    if ($widgets) {
        foreach ($widgets as $w) {
            $remove[] = $w;
        }
    }
    foreach ($remove as $n) {
        if ($n->parentNode) {
            $n->parentNode->removeChild($n);
        }
    }
}

function pd_split_oceanwp(string $html): array
{
    $dom = pd_load_dom($html);
    $xpath = new DOMXPath($dom);

    $title = '';
    $titleNode = $xpath->query('//title')->item(0);
    if ($titleNode) {
        $title = html_entity_decode(trim($titleNode->textContent), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    $description = '';
    $descNode = $xpath->query('//meta[@name="description"]')->item(0);
    if ($descNode instanceof DOMElement) {
        $description = html_entity_decode($descNode->getAttribute('content'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    $bodyClass = '';
    $body = $xpath->query('//body')->item(0);
    if ($body instanceof DOMElement) {
        $bodyClass = $body->getAttribute('class');
    }

    $extraCss = [];
    $sharedCss = [];
    $headMisc = '';
    $head = $xpath->query('//head')->item(0);
    if ($head) {
        foreach (iterator_to_array($head->childNodes) as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            if ($child->tagName === 'title') {
                continue;
            }
            if ($child->tagName === 'meta' && strtolower($child->getAttribute('name')) === 'description') {
                continue;
            }
            if ($child->tagName === 'link' && str_contains(strtolower($child->getAttribute('rel')), 'stylesheet')) {
                $href = pd_href_path($child->getAttribute('href'));
                $child->setAttribute('href', $href);
                if (!pd_is_shared_stylesheet($href)) {
                    $extraCss[] = $href;
                    continue;
                }
                $sharedCss[] = $href;
            }
            $chunk = pd_rewrite_html(pd_outer_html($child));
            $headMisc .= $chunk . "\n";
        }
    }

    $skip = $xpath->query('//a[contains(@class,"skip-link")]')->item(0);
    $outer = $xpath->query('//*[@id="outer-wrap"]')->item(0);
    $wrap = $xpath->query('//*[@id="wrap"]')->item(0);
    $topbar = $xpath->query('//*[@id="top-bar-wrap"]')->item(0);
    $siteHeader = $xpath->query('//*[@id="site-header"]')->item(0);
    $main = $xpath->query('//*[@id="main"]')->item(0);
    $footer = $xpath->query('//*[@id="footer"]')->item(0);
    $scroll = $xpath->query('//*[@id="scroll-top"]')->item(0);

    if ($siteHeader instanceof DOMElement) {
        pd_strip_commerce_node($siteHeader);
    }
    $mobileNav = $xpath->query('//*[@id="mobile-nav"]')->item(0);
    if ($mobileNav instanceof DOMElement) {
        pd_strip_commerce_node($mobileNav);
    }

    $headerBits = [];
    $skipInsideOuter = $skip && $outer && $outer->contains($skip);
    if ($skip instanceof DOMElement && !$skipInsideOuter) {
        $headerBits[] = pd_outer_html($skip);
    }
    if ($outer instanceof DOMElement) {
        $headerBits[] = pd_open_tag($outer);
    }
    if ($skip instanceof DOMElement && $skipInsideOuter) {
        $headerBits[] = pd_outer_html($skip);
    }
    if ($wrap instanceof DOMElement) {
        $headerBits[] = pd_open_tag($wrap);
    }
    if ($topbar instanceof DOMElement) {
        $headerBits[] = pd_outer_html($topbar);
    }
    if ($siteHeader instanceof DOMElement) {
        $headerBits[] = pd_outer_html($siteHeader);
    }

    $footerBits = [];
    if ($footer instanceof DOMElement) {
        $footerBits[] = pd_outer_html($footer);
    }
    $footerBits[] = '</div><!-- #wrap -->';
    $footerBits[] = '</div><!-- #outer-wrap -->';
    if ($scroll instanceof DOMElement) {
        $footerBits[] = pd_outer_html($scroll);
    }
    if ($body instanceof DOMElement) {
        foreach ($body->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            $id = $child->getAttribute('id');
            if ($id === 'outer-wrap' || $id === 'scroll-top') {
                continue;
            }
            if ($skip instanceof DOMElement && $child->isSameNode($skip)) {
                continue;
            }
            $footerBits[] = pd_outer_html($child);
        }
    }

    $header = pd_rewrite_html(implode("\n", $headerBits));
    $footerHtml = pd_rewrite_html(implode("\n", $footerBits));
    $mainHtml = $main ? pd_rewrite_html(pd_outer_html($main)) : '';

    $extraCss = array_values(array_unique($extraCss));

    return [
        'title' => $title,
        'description' => $description,
        'body_class' => $bodyClass,
        'extra_css' => $extraCss,
        'shared_css' => $sharedCss,
        'head_misc' => pd_rewrite_html($headMisc),
        'header' => $header,
        'main' => $mainHtml,
        'footer' => $footerHtml,
    ];
}

function pd_replace_add_to_cart(string $html, array $cfg): string
{
    $phone = htmlspecialchars($cfg['phone'] ?? '570-352-5501', ENT_QUOTES);
    $tel = htmlspecialchars($cfg['phone_tel'] ?? '+15703525501', ENT_QUOTES);
    $cta = '<p class="pd-call-to-buy">Call or text <a href="tel:' . $tel . '">' . $phone . '</a> or <a href="/contact-us/">contact us</a> about this piano.</p>';
    $replaced = preg_replace('#<form\b[^>]*\bcart\b[^>]*>.*?</form>#si', $cta, $html);
    if (is_string($replaced)) {
        $html = $replaced;
    }
    $html = preg_replace('#<button\b[^>]*>\s*Add to cart\s*</button>#i', '', $html) ?? $html;
    return $html;
}

function pd_page_php(array $parts): string
{
    $title = str_replace(["\\", "'"], ['\\\\', "\\'"], $parts['title'] ?? '');
    $description = str_replace(["\\", "'"], ['\\\\', "\\'"], $parts['description'] ?? '');
    $cssItems = $parts['extra_css'] ?? [];
    $cssPhp = "[\n";
    foreach ($cssItems as $href) {
        $cssPhp .= '        \'' . str_replace(['\\', "'"], ['\\\\', "\\'"], $href) . "',\n";
    }
    $cssPhp .= '    ]';
    $main = $parts['main'] ?? '';
    return <<<PHP
<?php
require_once \$_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
\$page = [
    'title' => '{$title}',
    'description' => '{$description}',
    'extra_css' => {$cssPhp},
];
require_once \$_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
{$main}
<?php require_once \$_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
PHP;
}

function pd_build_header_php(array $parts): string
{
    $headMisc = rtrim($parts['head_misc'] ?? '');
    $chrome = rtrim($parts['header'] ?? '');
    $bodyClass = htmlspecialchars($parts['body_class'] ?? '', ENT_QUOTES);
    return <<<PHP
<?php
/** @var array \$page */
\$cfg = pd_config();
\$title = htmlspecialchars(\$page['title'] ?? \$cfg['site_name'], ENT_QUOTES);
\$description = htmlspecialchars(\$page['description'] ?? '', ENT_QUOTES);
\$extraCss = \$page['extra_css'] ?? [];
?>
<!DOCTYPE html>
<html class="html" lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= \$title ?></title>
    <meta name="description" content="<?= \$description ?>">
{$headMisc}
<?php foreach (\$extraCss as \$href): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(\$href, ENT_QUOTES) ?>">
<?php endforeach; ?>
</head>
<body class="{$bodyClass}">
{$chrome}

PHP;
}

function pd_build_footer_php(array $parts): string
{
    return rtrim($parts['footer'] ?? '') . "\n</body>\n</html>\n";
}

