<?php

$root = dirname(__DIR__);
$page = file_get_contents($root . '/warrantys-and-return-policys/index.php');

expect(is_file($root . '/warrantys-and-return-policys/index.legacy.php'), 'warranty page has index.legacy.php');
expect(str_contains($page, '/wp-content/uploads/section-landing-pages.css'), 'warranty page loads landing CSS');
expect(str_contains($page, 'Warranties and Return Policies'), 'warranty page has title');
expect(str_contains($page, 'Disklavier_n_Silent_Piano_warranty.pdf'), 'Disklavier warranty PDF retained');
expect(str_contains($page, '070109_Acoustic_Piano_Warranty.pdf'), 'acoustic warranty PDF retained');
expect(str_contains($page, 'KEYBOARD_Hybrid_Piano_Warranty_2013.pdf'), 'hybrid warranty PDF retained');
expect(str_contains($page, 'Keyboard_Clavinova_2017'), 'Clavinova warranty PDF retained');
expect(str_contains($page, '30 days from the shipping date'), 'return window retained');
expect(str_contains($page, 'special orders cannot be returned'), 'special-order rule retained');
expect(str_contains($page, 'info@pianodepot.com'), 'return email retained');
expect(str_contains($page, '/contact-us/'), 'warranty page has contact CTA');
expect(!str_contains($page, 'rs-module-wrap'), 'warranty page has no revolution slider');
expect(!str_contains($page, 'owp-quick-view'), 'warranty page has no quick view');
expect(!str_contains($page, 'wc-block-product-search'), 'warranty page has no product search');
expect(!str_contains($page, 'piano_internal-links'), 'warranty page has no leftover category nav');
