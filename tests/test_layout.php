<?php

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
require_once dirname(__DIR__) . '/partials/config.php';

ob_start();
$page = [
    'title' => 'Layout Test | Piano Depot',
    'description' => 'Test description',
    'extra_css' => ['/wp-content/uploads/elementor/css/post-3068.css'],
];
require dirname(__DIR__) . '/partials/header.php';
echo '<main id="main">body</main>';
require dirname(__DIR__) . '/partials/footer.php';
$html = ob_get_clean();

expect(str_contains($html, '<title>Layout Test | Piano Depot</title>'), 'title from $page');
expect(str_contains($html, 'name="description"'), 'meta description tag exists');
expect(str_contains($html, 'Test description'), 'description from $page');
expect(str_contains($html, '/wp-content/uploads/elementor/css/post-3068.css'), 'extra_css printed');
expect(str_contains($html, 'Skip to content'), 'skip link');
expect(str_contains($html, '570-352-5501'), 'phone in chrome');
expect(str_contains($html, '225 W. Lackawanna Ave.'), 'address in chrome');
expect(str_contains($html, 'href="/"'), 'home link');
expect(str_contains($html, 'id="site-header"') || str_contains($html, 'site-header'), 'header landmark');
expect(str_contains($html, 'Piano Depot'), 'site name in chrome');
