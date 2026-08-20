<?php

function pd_http_get(string $path): array
{
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true, 'follow_location' => 0]]);
    $body = @file_get_contents('http://127.0.0.1:8003' . $path, false, $ctx);
    $code = 0;
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
        $code = (int) $m[1];
    }
    return ['code' => $code, 'body' => $body === false ? '' : $body];
}

$probe = @fsockopen('127.0.0.1', 8003, $errno, $errstr, 0.5);
if ($probe === false) {
    echo "SKIP: start php -S localhost:8003 router.php\n";
    return;
}
fclose($probe);

$home = pd_http_get('/');
expect($home['code'] === 200, 'GET / is 200');
expect(str_contains($home['body'], 'id="site-header"'), 'home has header');
expect(str_contains($home['body'], 'id="footer"'), 'home has footer');
expect(!str_contains($home['body'], 'https://pianodepot.com/wp-content'), 'home does not load live wp-content');

$cat = pd_http_get('/pianos-we-sell/');
expect($cat['code'] === 200, 'GET /pianos-we-sell/ is 200');
expect(str_contains($cat['body'], 'id="site-header"'), 'catalog has header');

$prod = pd_http_get('/product/p-515/');
expect($prod['code'] === 200, 'GET /product/p-515/ is 200');
expect(str_contains($prod['body'], 'id="site-header"'), 'product has header');
expect(str_contains($prod['body'], '570-352-5501'), 'product has phone');
expect(!str_contains($prod['body'], 'Add to cart'), 'product has no add to cart');

$contact = pd_http_get('/contact-us/');
expect($contact['code'] === 200, 'GET /contact-us/ is 200');
expect(str_contains($contact['body'], 'forms/send.php') || str_contains($contact['body'], 'gform'), 'contact has form');

$missing = pd_http_get('/no-such-page-xyz/');
expect($missing['code'] === 404, 'unknown url is 404');
expect(str_contains($missing['body'], 'Page not found'), '404 copy');
expect(str_contains($missing['body'], 'Pianos we sell'), '404 link to catalog');

$account = pd_http_get('/account/');
expect($account['code'] === 404, '/account/ is 404');

$cart = pd_http_get('/cart/');
expect($cart['code'] === 404, '/cart/ is 404');
