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
