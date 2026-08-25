<?php

$root = dirname(__DIR__);
$page = file_get_contents($root . '/apply-for-credit-at-pianodepot-com/index.php');

expect(is_file($root . '/apply-for-credit-at-pianodepot-com/index.legacy.php'), 'credit page has index.legacy.php');
expect(str_contains($page, '/wp-content/uploads/section-landing-pages.css'), 'credit page loads landing CSS');
expect(str_contains($page, 'Apply for Credit'), 'credit page has title');
expect(str_contains($page, 'simplefinancing.umwsb.com/embed/prequal-loan-application?id=dd13534d-a6f1-4b1e-a37f-d2c7993305c2'), 'financing embed retained');
expect(str_contains($page, "\$cfg['phone']") || str_contains($page, '570-352-5501'), 'credit page has phone');
expect(!str_contains($page, 'rs-module-wrap'), 'credit page has no revolution slider');
expect(!str_contains($page, 'wc-block-product-search'), 'credit page has no product search');
expect(!str_contains($page, 'piano_internal-links'), 'credit page has no leftover category nav');
