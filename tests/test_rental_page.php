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

expect_rental(str_contains($page, "'title' => 'Piano Rental Service'"), 'rental page title');
expect_rental(str_contains($page, 'rental programs include lessons'), 'lessons source detail retained');
expect_rental(str_contains($page, 'include an in-tune piano'), 'in-tune piano source detail retained');
expect_rental(str_contains($page, 'tuning maintenance agreement'), 'maintenance source detail retained');
expect_rental(str_contains($page, "'cta_href' => '/contact-us/'"), 'rental contact call to action');
expect_rental(str_contains($header, 'href="/piano-rental-service/"') && str_contains($header, 'Piano Rental Service'), 'desktop service navigation includes rental');
expect_rental(str_contains($footer, '<a href="/piano-rental-service/">Our Piano Rental Service</a>'), 'footer rental link is internal');
expect_rental(substr_count($footer, '<a href="/piano-rental-service/">Piano Rental Service</a>') === 1, 'mobile service navigation includes rental once');
expect_rental(str_contains($services, "'href' => '/piano-rental-service/'"), 'services landing includes rental');
