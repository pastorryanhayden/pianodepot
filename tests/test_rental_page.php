<?php

function expect_rental(bool $condition, string $message): void
{
	if (!$condition) {
		fwrite(STDERR, "FAIL: {$message}\n");
		exit(1);
	}

	echo "PASS: {$message}\n";
}

$root = dirname(__DIR__);
$page = file_get_contents($root . '/piano-rental-service/index.php');
$header = file_get_contents($root . '/partials/header.php');
$footer = file_get_contents($root . '/partials/footer.php');
$services = file_get_contents($root . '/services-we-offer/index.php');

expect_rental(str_contains($page, 'Short-Term Piano Rentals for Special Occasions'), 'short-term special-occasion title');
expect_rental(str_contains($page, 'used grand pianos'), 'used grand rentals retained');
expect_rental(str_contains($page, 'used upright pianos'), 'used upright rentals retained');
expect_rental(str_contains($page, 'select available digital pianos'), 'select digital rentals retained');
expect_rental(str_contains($page, 'Availability varies.'), 'availability notice retained');
expect_rental(str_contains($page, 'Contact Piano Depot for available instruments and pricing.'), 'contact and pricing direction retained');
expect_rental(str_contains($page, '/wp-content/uploads/piano-rental-special-occasion.png'), 'approved rental hero image used');
expect_rental(str_contains($page, 'href="/contact-us/"'), 'rental contact call to action');
expect_rental(str_contains($page, 'Considering a longer-term piano rental?'), 'longer-term interest invitation');
expect_rental(str_contains($page, 'We are exploring future rental options for homes and students. Contact us to let us know what you would need.'), 'future exploration is explicit');
expect_rental(!preg_match('/lesson|maintenance agreement|monthly payment|one-year/i', $page), 'unsupported rental claims remain removed');
expect_rental(str_contains($header, 'href="/piano-rental-service/"') && str_contains($header, 'Piano Rental Service'), 'desktop service navigation includes rental');
expect_rental(str_contains($footer, '<a href="/piano-rental-service/">Our Piano Rental Service</a>'), 'footer rental link is internal');
expect_rental(substr_count($footer, '<a href="/piano-rental-service/">Piano Rental Service</a>') === 1, 'mobile service navigation includes rental once');
expect_rental(str_contains($services, "'href' => '/piano-rental-service/'"), 'services landing includes rental');
expect_rental(!preg_match('/lessons|maintenance agreement|monthly payment/i', $services), 'services card removes old rental claims');
