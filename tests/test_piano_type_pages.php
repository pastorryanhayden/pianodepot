<?php

$root = dirname(__DIR__);

$types = [
    'disklavier-pianos' => 'Disklavier Pianos',
    'acoustic-grand-pianos' => 'Acoustic Grand Pianos',
    'acoustic-silent-trans-acoustic-pianos' => 'Silent & TransAcoustic Pianos',
    'acoustic-upright-pianos' => 'Acoustic Upright Pianos',
    'portable-digital-pianos' => 'Portable Digital Pianos',
    'workstation-keyboards' => 'Workstation Keyboards',
    'used-and-refurbished' => 'Used & Refurbished Pianos',
];

$forbidden = [
    'rs-module-wrap',
    'owp-quick-view',
    'wc-block-product-search',
    'piano_internal-links',
];

$disklavierVideos = ['T9Qv5B1Eb-k', 'U1cNpWSI9Nw', 'Xu36GOKXs5M', 'kvUFUKFUDC4', 'hlvBms8IW7o'];

foreach ($types as $slug => $title) {
    $legacy = $root . '/' . $slug . '/index.legacy.php';
    $page = $root . '/' . $slug . '/index.php';
    $html = file_get_contents($page);

    expect(is_file($legacy), $slug . ' has index.legacy.php');
    expect(str_contains($html, "partials/piano-type-catalog.php"), $slug . ' uses catalog partial');
    expect(str_contains($html, '/wp-content/uploads/piano-type-pages.css'), $slug . ' loads type-page CSS');
    expect(str_contains($html, $title), $slug . ' has hero title');
    expect(str_contains($html, '/contact-us/'), $slug . ' has appointment CTA');

    foreach ($forbidden as $needle) {
        expect(!str_contains($html, $needle), $slug . ' does not contain ' . $needle);
    }
}

$nav = file_get_contents($root . '/partials/pianos-category-nav.php');
expect(str_contains($nav, 'piano-category-nav__current'), 'collection nav can mark the current type');

$partialPath = $root . '/partials/piano-type-catalog.php';
expect(is_file($partialPath), 'catalog partial exists');
$partial = is_file($partialPath) ? file_get_contents($partialPath) : '';
expect(str_contains($partial, 'piano-type-hero'), 'partial renders photo hero');
expect(str_contains($partial, 'youtube.com/embed/'), 'partial can render videos');

$disk = file_get_contents($root . '/disklavier-pianos/index.php');
foreach ($disklavierVideos as $id) {
    expect(substr_count($disk, $id) === 1, 'Disklavier includes ' . $id . ' once');
}

$http = null;
foreach ([8006, 8003] as $port) {
    $probe = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.3);
    if ($probe === false) {
        continue;
    }
    fclose($probe);
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true, 'follow_location' => 0]]);
    $body = @file_get_contents('http://127.0.0.1:' . $port . '/disklavier-pianos/', false, $ctx);
    $code = 0;
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
        $code = (int) $m[1];
    }
    $http = ['code' => $code, 'body' => $body === false ? '' : $body];
    break;
}

if ($http === null) {
    echo "SKIP HTTP: start php -S localhost:8006 router.php\n";
} else {
    expect($http['code'] === 200, 'GET /disklavier-pianos/ is 200');
    expect(str_contains($http['body'], 'Disklavier Pianos'), 'HTTP Disklavier has title');
    expect(str_contains($http['body'], 'Schedule an Appointment'), 'HTTP Disklavier has appointment CTA');
    expect(!str_contains($http['body'], 'rs-module-wrap'), 'HTTP Disklavier has no slider');
}
