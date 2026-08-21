<?php
$clavinova_series = [
	'CLP Series' => [
		'intro' => 'The closest experience to an acoustic grand piano, with Yamaha CFX and Bösendorfer Imperial voices, GrandTouch keyboard actions, Virtual Resonance Modeling, and Bluetooth connectivity.',
		'models' => [
			['CLP-825', '2,099.99', 'clp-825.jpg', 'GrandTouch-S action, 10 voices, and a compact console cabinet.'],
			['CLP-835', '3,199.99', 'clp-835.jpg', 'GrandTouch-S action, LCD display, 38 voices, and expanded lesson features.'],
			['CLP-845', '3,999.99', 'clp-845.jpg', 'Wooden GrandTouch-S keys and a powerful two-way speaker system.'],
			['CLP-865GP', '6,499.99', 'clp-865gp.jpg', 'Grand-style cabinet with GrandTouch-S action and immersive sound.'],
			['CLP-875', '5,299.99', 'clp-875.jpg', 'GrandTouch wooden-key action and three-way speaker system.'],
			['CLP-885', '6,999.99', 'clp-885.jpg', 'Counterweighted GrandTouch action and flagship upright sound system.'],
			['CLP-895GP', '8,899.99', 'clp-895gp.jpg', 'Flagship grand cabinet, counterweighted action, and Grand Acoustic Imaging.'],
		],
	],
	'CSP Series' => [
		'intro' => 'Designed for learning, arranging, singing, and playing favorite songs through Yamaha’s Smart Pianist app and Stream Lights.',
		'models' => [
			['CSP-255', '3,999.99', 'csp-255.jpg', 'GrandTouch-S action, Stream Lights, accompaniment styles, and microphone input.'],
			['CSP-275', '5,399.99', 'csp-275.jpg', 'Wooden GrandTouch-S keys with a more powerful speaker system.'],
			['CSP-295', '7,599.99', 'csp-295.jpg', 'GrandTouch action, expanded voices, and premium three-way sound.'],
			['CSP-295GP', '10,999.99', 'csp-295gp.jpg', 'The flagship CSP experience in an elegant grand-style cabinet.'],
		],
	],
	'CVP Series' => [
		'intro' => 'Yamaha’s most entertaining Clavinova line, combining a premium piano experience with touchscreen control, accompaniment, singing, recording, and hundreds of instrument voices.',
		'models' => [
			['CVP-905', '8,499.99', 'cvp-905.jpg', 'GrandTouch action, 7-inch touchscreen, guide lamps, and 525 accompaniment styles.'],
			['CVP-909', '13,199.99', 'cvp-909.jpg', 'Flagship upright CVP with a 9-inch touchscreen and premium sound system.'],
			['CVP-909GP', '18,999.99', 'cvp-909gp.jpg', 'Flagship CVP technology and sound in a grand-style cabinet.'],
		],
	],
];
?>
<main id="main" class="site-main clr clavinova-catalog" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<section class="clavinova-hero">
		<p class="clavinova-kicker">Current Yamaha Clavinova Collection</p>
		<h1>Find Your Clavinova</h1>
		<p>Compare Yamaha’s current CLP, CSP, and CVP models. Piano Depot provides local guidance, delivery, setup, and service from our Olyphant showroom.</p>
		<div class="clavinova-actions"><a class="clavinova-button" href="/contact-us/">Schedule an Appointment</a><a class="clavinova-button clavinova-button--light" href="/clavinova-sale/">See In-Stock Sale Models</a></div>
	</section>
	<div class="clavinova-series-nav" aria-label="Clavinova series"><a href="#clp-series">CLP: Authentic Piano</a><a href="#csp-series">CSP: Learn &amp; Create</a><a href="#cvp-series">CVP: Play &amp; Entertain</a></div>
	<div class="clavinova-catalog__content">
		<?php foreach ($clavinova_series as $series => $data) : $series_id = strtolower(str_replace(' ', '-', $series)); ?>
		<section class="clavinova-series" id="<?= htmlspecialchars($series_id) ?>">
			<div class="clavinova-series__heading"><h2><?= htmlspecialchars($series) ?></h2><p><?= htmlspecialchars($data['intro']) ?></p></div>
			<div class="clavinova-grid">
				<?php foreach ($data['models'] as [$model, $price, $image, $description]) : $yamaha_slug = strtolower($model); ?>
				<article class="clavinova-card">
					<a class="clavinova-card__image" href="https://usa.yamaha.com/products/musical_instruments/pianos/clavinova/<?= htmlspecialchars($yamaha_slug) ?>/index.html" target="_blank" rel="noopener"><img src="/wp-content/uploads/2026/08/clavinova-current/<?= htmlspecialchars($image) ?>" alt="Yamaha <?= htmlspecialchars($model) ?> Clavinova"></a>
					<div class="clavinova-card__body"><p class="clavinova-card__series"><?= htmlspecialchars($series) ?></p><h3><?= htmlspecialchars($model) ?></h3><p><?= htmlspecialchars($description) ?></p><p class="clavinova-card__price"><span>MAP starting at</span> $<?= htmlspecialchars($price) ?></p><a class="clavinova-card__link" href="/contact-us/?model=<?= rawurlencode($model) ?>">Ask About Availability</a></div>
				</article>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endforeach; ?>
		<section class="clavinova-price-note"><h2>About Clavinova Pricing</h2><p>Prices shown are current U.S. minimum advertised prices (MAP) for the least-expensive available finish. Polished finishes may have a higher MAP. Prices and availability can change; contact Piano Depot for current inventory and finish options.</p></section>
	</div>
</main>
