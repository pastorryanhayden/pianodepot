<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Piano Closeout Sales in Olyphant, PA | Piano Depot',
    'description' => 'Discover unbeatable deals at Piano Depot\'s piano closeout sales in Olyphant, PA! Shop a wide selection of quality pianos. Limited stock–don’t miss out!',
    'extra_css' => [
        '/wp-content/uploads/piano-type-pages.css',
        '/wp-content/uploads/piano-depot-closeout-products.css',
    ],
];
$catalog = ['nav' => '/piano-closeout-sales-in-olyphant-pa/'];
$closeoutProducts = require __DIR__ . '/products.php';
$cfg = pd_config();
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr piano-type-catalog" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<header class="piano-type-hero" style="background-image: url('/wp-content/uploads/2025/02/Kawai-2.jpg');">
		<div class="piano-type-hero__inner">
			<p class="piano-type-hero__eyebrow">Closeout Sales</p>
			<h1>Piano Closeout Sales</h1>
			<p>Open-box, refurbished, and last-chance pianos at discounted prices. Once they’re gone, they’re gone. Call or text ahead — ask for Frank Bissol.</p>
			<div class="piano-type-hero__actions">
				<a class="piano-type-button" href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>">Call Today</a>
				<a class="piano-type-button piano-type-button--light" href="/contact-us/">Schedule an Appointment</a>
			</div>
		</div>
	</header>
	<div class="piano-type-content">
		<section class="closeout-lead">
			<h2>Unbeatable deals on discounted pianos</h2>
			<p>Whether you’re looking for a first piano or an upgrade, these closeout instruments are inspected, tuned, and ready to play. From <a href="/acoustic-grand-pianos/">acoustic grands</a> to <a href="/workstation-keyboards/">Yamaha workstations</a>, stock is limited.</p>
			<ul>
				<li>Clearance prices on last-chance models</li>
				<li>Open-box deals and refurbished savings</li>
				<li>Every piano inspected and fully tuned</li>
			</ul>
		</section>
		<section class="closeout-products" data-closeout-products>
			<div class="closeout-products__inner">
				<?php foreach ($closeoutProducts as $closeoutProduct) {
					require __DIR__ . '/product-entry.php';
				} ?>
			</div>
		</section>
		<section class="piano-type-cta">
			<div class="piano-type-cta__copy">
				<h2>Call about a closeout piano</h2>
				<p>Inventory changes. Please call or text ahead. Ask for Frank Bissol. Call or text <a class="piano-type-cta__phone" href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>"><?= htmlspecialchars($cfg['phone']) ?></a>.</p>
			</div>
			<a class="piano-type-button" href="/contact-us/">Schedule an Appointment</a>
		</section>
	</div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
