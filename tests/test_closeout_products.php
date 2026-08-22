<?php

$root = dirname(__DIR__);
$products = require $root . '/piano-closeout-sales-in-olyphant-pa/products.php';
$template = $root . '/piano-closeout-sales-in-olyphant-pa/product-entry.php';

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
expect(str_contains($rendered, 'closeout-product__price'), 'closeout prices render as a distinct line');

$pageSource = file_get_contents($root . '/piano-closeout-sales-in-olyphant-pa/index.php');
expect(is_file($root . '/piano-closeout-sales-in-olyphant-pa/index.legacy.php'), 'closeout has index.legacy.php');
expect(str_contains($pageSource, '/wp-content/uploads/piano-type-pages.css'), 'closeout loads type-page CSS');
expect(str_contains($pageSource, 'closeout-products'), 'closeout product list remains');
expect(str_contains($pageSource, "'nav' => '/piano-closeout-sales-in-olyphant-pa/'"), 'closeout marks the current collection chip');
expect(!str_contains($pageSource, 'rs-module-wrap'), 'closeout has no revolution slider');
expect(!str_contains($pageSource, 'elementor-section-full_width'), 'closeout has no full-width elementor sections');
