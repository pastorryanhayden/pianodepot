<?php

require_once dirname(__DIR__) . '/tools/lib.php';

$fixture = <<<HTML
<!DOCTYPE html><html><head><title>Home Title</title>
<meta name="description" content="Home desc">
<link rel="stylesheet" href="https://pianodepot.com/wp-content/themes/oceanwp/assets/css/style.min.css">
<link rel="stylesheet" href="https://pianodepot.com/wp-content/uploads/elementor/css/post-3068.css">
</head><body>
<a class="skip-link" href="#main">Skip to content</a>
<div id="outer-wrap"><div id="wrap">
<div id="top-bar-wrap">phone</div>
<header id="site-header"><nav><a href="https://pianodepot.com/account/">ACCOUNT</a>
<a class="wcmenucart" href="https://pianodepot.com/cart/">0</a>
<a href="https://pianodepot.com/pianos-we-sell/">PIANOS WE SELL</a>
</nav></header>
<main id="main"><h1>Hello</h1></main>
<footer id="footer">foot</footer>
</div></div>
<a id="scroll-top" href="#top">Top</a>
</body></html>
HTML;

$parts = pd_split_oceanwp($fixture);
expect($parts['title'] === 'Home Title', 'title');
expect($parts['description'] === 'Home desc', 'description');
expect(str_contains($parts['main'], '<h1>Hello</h1>'), 'main body');
expect(str_contains($parts['header'], 'id="site-header"'), 'header has site-header');
expect(str_contains($parts['header'], 'id="top-bar-wrap"'), 'header has top bar');
expect(!str_contains($parts['header'], '/account/'), 'account stripped');
expect(!str_contains($parts['header'], '/cart/'), 'cart stripped');
expect(str_contains($parts['header'], '/pianos-we-sell/'), 'catalog link kept');
expect(str_contains($parts['footer'], 'id="footer"'), 'footer');
expect(str_contains($parts['footer'], 'scroll-top'), 'scroll-top in footer');
expect(in_array('/wp-content/uploads/elementor/css/post-3068.css', $parts['extra_css'], true), 'page extra css');
expect(!in_array('/wp-content/themes/oceanwp/assets/css/style.min.css', $parts['extra_css'], true), 'shared css is not extra');
expect(pd_is_shared_stylesheet('/wp-content/themes/oceanwp/assets/css/style.min.css') === true, 'theme css is shared');
expect(pd_is_shared_stylesheet('/wp-content/uploads/elementor/css/post-3068.css') === false, 'post css is not shared');
