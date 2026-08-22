<?php

$products = require dirname(__DIR__) . '/piano-closeout-sales-in-olyphant-pa/products.php';
$template = dirname(__DIR__) . '/piano-closeout-sales-in-olyphant-pa/product-entry.php';

expect(count($products) === 8, 'closeout inventory count');

$rendered = '';
foreach ($products as $closeoutProduct) {
    ob_start();
    require $template;
    $rendered .= ob_get_clean();
}

expect(substr_count($rendered, 'data-closeout-product') === count($products), 'every closeout entry uses template');
expect(substr_count($rendered, 'closeout-product--with-image') === 5, 'image entries render image layout');
expect(substr_count($rendered, 'closeout-product--without-image') === 3, 'optional-image entries render without blank image layout');
expect(substr_count($rendered, '<img ') === 5, 'only configured closeout images render');
expect(str_contains($rendered, 'Baldwin Refurbished Model 243'), 'Baldwin closeout retained');
expect(str_contains($rendered, 'PSR-SX900'), 'PSR-SX closeout retained');
expect(str_contains($rendered, 'CLP, CVP, and CSP Models'), 'Clavinova closeout retained');

$pageSource = file_get_contents(dirname(__DIR__) . '/piano-closeout-sales-in-olyphant-pa/index.php');
expect(str_contains($pageSource, 'closeout-products elementor-section-boxed'), 'closeout entries stay in boxed container');
expect(!preg_match('/elementor-element-(61526d0|0586d54)[^\n]*elementor-section-full_width/', $pageSource), 'reported full-width defects removed');
