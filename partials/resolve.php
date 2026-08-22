<?php

function pd_resolve(string $uri, string $root): array
{
    $path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?? '/');
    if ($path === '' || $path === false) {
        $path = '/';
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    $blocked = ['/tests', '/partials', '/tools', '/docs', '/scrape', '/.git', '/.env'];
    foreach ($blocked as $prefix) {
        if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
            return ['kind' => 'not_found', 'path' => $root . '/404.php'];
        }
    }

    if ($path === '/') {
        return ['kind' => 'php', 'path' => $root . '/index.php'];
    }

    $full = $root . $path;
    if (is_file($full)) {
        return ['kind' => 'file', 'path' => $full];
    }

    $dir = rtrim($full, '/');
    if (is_dir($dir) && is_file($dir . '/index.php')) {
        return ['kind' => 'php', 'path' => $dir . '/index.php'];
    }

    return ['kind' => 'not_found', 'path' => $root . '/404.php'];
}
