<?php

require_once dirname(__DIR__) . '/tools/lib.php';

expect(pd_should_clone('/') === true, 'clone home');
expect(pd_should_clone('/pianos-we-sell/') === true, 'clone catalog');
expect(pd_should_clone('/product/p-515/') === true, 'clone product');
expect(pd_should_clone('/cart/') === false, 'skip cart');
expect(pd_should_clone('/checkout/') === false, 'skip checkout');
expect(pd_should_clone('/account/') === false, 'skip account');
expect(pd_should_clone('/shop/') === false, 'skip shop');
expect(pd_should_clone('/home/') === false, 'skip old home');
expect(pd_should_clone('/home-11-1-23-holiday-promotion/') === false, 'skip holiday home');
expect(pd_should_clone('/disklavier-pianos-2/') === false, 'skip -2 duplicate');
expect(pd_should_clone('/gb1k-gc-series-5-to-5-8-2/') === false, 'skip another -2');
expect(pd_should_clone('/contact-us/') === true, 'clone contact');

$root = '/tmp/pd-root';
expect(pd_url_to_local_path('https://pianodepot.com/pianos-we-sell/', $root) === $root . '/pianos-we-sell/index.html', 'page path');
expect(pd_url_to_local_path('https://pianodepot.com/', $root) === $root . '/index.html', 'home page path');
expect(pd_url_to_local_path('https://pianodepot.com/wp-content/uploads/2021/03/piano_depot.png', $root) === $root . '/wp-content/uploads/2021/03/piano_depot.png', 'upload path');
expect(pd_url_to_local_path('http://pianodepot.us/wp-content/uploads/revslider/x.jpg', $root) === $root . '/wp-content/uploads/revslider/x.jpg', 'us-host upload');
expect(pd_url_to_local_path('https://fonts.googleapis.com/css?family=Cabin', $root) === null, 'skip google fonts');
expect(pd_url_to_local_path('https://player.vimeo.com/video/1', $root) === null, 'skip vimeo');

$html = pd_rewrite_html('<a href="https://pianodepot.com/contact-us/">x</a><img src="http://pianodepot.com/wp-content/uploads/a.jpg">');
expect(str_contains($html, 'href="/contact-us/"'), 'rewrite https host to root');
expect(str_contains($html, 'src="/wp-content/uploads/a.jpg"'), 'rewrite http host to root');
expect(!str_contains($html, 'pianodepot.com'), 'no pianodepot.com left in this snippet');
