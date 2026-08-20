<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/tools/lib.php';

$cfg = pd_config();
$html = <<<HTML
<form class="cart" action="https://pianodepot.com/product/p-515/" method="post">
  <input type="number" name="quantity" value="1">
  <button type="submit" name="add-to-cart" value="123" class="single_add_to_cart_button">Add to cart</button>
</form>
<p class="price"><span class="woocommerce-Price-amount">$1,299.00</span></p>
HTML;

$out = pd_replace_add_to_cart($html, $cfg);
expect(!str_contains($out, 'Add to cart'), 'button gone');
expect(!str_contains(strtolower($out), 'name="quantity"'), 'quantity gone');
expect(str_contains($out, '570-352-5501'), 'phone shown');
expect(str_contains($out, 'tel:+15703525501'), 'tel link');
expect(str_contains($out, '/contact-us/'), 'contact link');
expect(str_contains($out, '$1,299.00'), 'price kept');
