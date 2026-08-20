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
