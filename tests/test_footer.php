<?php

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
require_once dirname(__DIR__) . '/partials/config.php';

ob_start();
require dirname(__DIR__) . '/privacy-policy/index.php';
$privacyHtml = ob_get_clean();

expect(substr_count($privacyHtml, '<footer id="footer"') === 1, 'privacy renders one shared footer');
expect(substr_count($privacyHtml, 'id="mobile-nav"') === 1, 'privacy renders one shared mobile menu');
expect(substr_count($privacyHtml, '/wp-content/uploads/elementor/css/post-14.css') === 1, 'privacy loads canonical footer layout css once');

ob_start();
$page = [
    'title' => 'Footer Fallback Test | Piano Depot',
    'description' => '',
    'extra_css' => [],
];
require dirname(__DIR__) . '/partials/header.php';
require dirname(__DIR__) . '/partials/footer.php';
$fallbackHtml = ob_get_clean();

expect(substr_count($fallbackHtml, '/wp-content/uploads/elementor/css/post-14.css') === 1, 'header supplies canonical footer css');
expect(substr_count($fallbackHtml, '<footer id="footer"') === 1, 'shared footer renders once');
