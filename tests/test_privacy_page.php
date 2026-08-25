<?php

$root = dirname(__DIR__);
$page = file_get_contents($root . '/privacy-policy/index.php');

expect(is_file($root . '/privacy-policy/index.legacy.php'), 'privacy page has index.legacy.php');
expect(str_contains($page, '/wp-content/uploads/section-landing-pages.css'), 'privacy page loads landing CSS');
expect(str_contains($page, 'Privacy Policy'), 'privacy page has title');
expect(str_contains($page, 'November 15, 2021'), 'privacy last-updated date retained');
expect(str_contains($page, 'Bissol’s Piano Depot') || str_contains($page, "Bissol's Piano Depot"), 'legal company name retained');
expect(str_contains($page, 'bissol@pianodepot.com'), 'bissol contact email retained');
expect(str_contains($page, 'info@pianodepot.com'), 'info contact email retained');
expect(str_contains($page, 'https://pianodepot.com/'), 'website URL filled in');
expect(str_contains($page, 'https://www.paypal.com/us/webapps/mpp/ua/privacy-full'), 'PayPal privacy URL retained');
expect(str_contains($page, 'https://policies.google.com/privacy'), 'Google privacy URL retained');
expect(str_contains($page, 'https://app.termly.io/notify/bda9f2e5-4719-4293-ab6b-2e24d586fdcf'), 'Termly data-request URL retained');
expect(str_contains($page, 'P.O. Box 258'), 'mailing address retained');
expect(str_contains($page, 'Clifford, PA 18413'), 'mailing city retained');
expect(str_contains($page, '90 days'), 'retention period retained');
expect(str_contains($page, 'Shine The Light'), 'California Shine the Light retained');
expect(str_contains($page, 'Do-Not-Track'), 'DNT section retained');
expect(!str_contains($page, 'rs-module-wrap'), 'privacy page has no revolution slider');
expect(!str_contains($page, 'wc-block-product-search'), 'privacy page has no product search');
expect(!str_contains($page, 'gform_wrapper'), 'privacy page has no leftover Gravity Form');
expect(!str_contains($page, 'data-custom-class'), 'privacy page has no Termly generator markup');
expect(!str_contains($page, '<bdt'), 'privacy page has no Termly bdt tags');
