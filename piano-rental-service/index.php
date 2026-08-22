<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
	'title' => 'Short-Term Piano Rentals for Special Occasions | Piano Depot',
	'description' => 'Piano Depot rents used grand pianos, used upright pianos, and select available digital pianos for special occasions.',
	'extra_css' => [
		'/wp-content/uploads/elementor/css/post-349.css',
		'/wp-content/uploads/elementor/css/post-11.css',
		'/wp-content/uploads/elementor/css/post-14.css',
		'/wp-content/uploads/piano-rental-service.css',
	],
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr rental-service" role="main">
	<section class="rental-service__hero">
		<div class="rental-service__hero-copy">
			<p class="rental-service__eyebrow">Piano Depot Rental Service</p>
			<h1>Short-Term Piano Rentals for Special Occasions</h1>
			<p>Piano Depot rents used grand pianos, used upright pianos, and select available digital pianos for special occasions.</p>
			<a class="rental-service__button" href="/contact-us/">Contact Piano Depot</a>
		</div>
		<figure class="rental-service__hero-image">
			<img src="/wp-content/uploads/piano-rental-special-occasion.png" alt="Black grand piano being played at an elegant special occasion" width="1536" height="1024">
		</figure>
	</section>

	<section class="rental-service__details" aria-labelledby="rental-instruments-heading">
		<div>
			<p class="rental-service__eyebrow">Available Rental Instruments</p>
			<h2 id="rental-instruments-heading">Pianos for Your Special Occasion</h2>
			<ul>
				<li>Used grand pianos</li>
				<li>Used upright pianos</li>
				<li>Select available digital pianos</li>
			</ul>
			<p><strong>Availability varies.</strong></p>
		</div>
		<div class="rental-service__contact">
			<h2>Ask About Available Instruments and Pricing</h2>
			<p>Contact Piano Depot for available instruments and pricing.</p>
			<a class="rental-service__button" href="/contact-us/">Contact Us</a>
		</div>
	</section>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
