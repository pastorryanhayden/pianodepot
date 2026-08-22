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
expect(str_contains($fallbackHtml, '<a href="/about-us/">About Us</a>'), 'footer About Us is internal');
expect(str_contains($fallbackHtml, '<a href="https://maps.app.goo.gl/wCwevR264g8WTPHt9" target="_blank" rel="noopener noreferrer">Directions to our Store Display Olyphant</a>'), 'footer directions uses approved Maps link');
expect(str_contains($fallbackHtml, '<a href="/piano-rental-service/">Our Piano Rental Service</a>'), 'footer rental service is internal');
expect(str_contains($fallbackHtml, '<a href="/#new-pianos-we-sell">New Pianos We Sell</a>'), 'new pianos footer link uses homepage inventory anchor');
expect(str_contains($fallbackHtml, '<a href="https://www.pianoorgandepot.com/Hammond-Organ-Sales.html" target="_blank" rel="noopener noreferrer">Vintage Hammond Organs For Sale</a>'), 'footer Hammond sales uses approved external link');

$home = file_get_contents(dirname(__DIR__) . '/index.php');
expect(str_contains($home, 'id="new-pianos-we-sell"'), 'homepage Yamaha inventory section has stable anchor');

$footerCss = file_get_contents(dirname(__DIR__) . '/wp-content/themes/oceanwp-child/style.css');
expect(str_contains($footerCss, '.rebuilt-footer-menu > .elementor-container'), 'compact footer layout is shared globally');
expect(!str_contains($footerCss, 'home-footer-trial'), 'compact footer is not limited to homepage');
