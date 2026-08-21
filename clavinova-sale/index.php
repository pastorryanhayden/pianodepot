<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = ['title' => 'Clavinova Sale | In-Stock Yamaha Digital Pianos | Piano Depot', 'description' => 'Shop remaining in-stock Yamaha Clavinova and hybrid piano models at Piano Depot in Olyphant, PA. Limited quantities available.', 'extra_css' => ['/wp-content/uploads/piano-depot-category-pages.css', '/wp-content/uploads/clavinova-catalog.css']];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
$sale_models = [
	['CLP-735B', 'Matte Black', '$3,199.00', '$2,899.99'],
	['CLP-735PE', 'Polished Ebony', '$3,699.00', '$3,399.99'],
	['CLP-735R', 'Dark Rosewood', '$3,199.00', '$2,899.99'],
	['CLP-735WH', 'Matte White', '$3,199.00', '$2,899.99'],
];
?>
<main id="main" class="site-main clr clavinova-catalog clavinova-sale" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<section class="clavinova-hero clavinova-hero--sale"><p class="clavinova-kicker">Limited In-Stock Models</p><h1>CLP-735 Closeout Sale</h1><p>Save on remaining Yamaha CLP-735 digital pianos in four beautiful finishes. Every advertised sale price follows Yamaha’s current minimum advertised price. Quantities are limited.</p><a class="clavinova-button" href="/contact-us/">Check Current Availability</a></section>
	<div class="clavinova-catalog__content"><div class="clavinova-grid">
		<?php foreach ($sale_models as [$model, $finish, $regular_price, $map_price]) : ?>
		<article class="clavinova-card"><div class="clavinova-card__image"><img src="/wp-content/uploads/2021/06/mEpf7dj4.jpeg" alt="Yamaha <?= htmlspecialchars($model) ?> in <?= htmlspecialchars($finish) ?>"></div><div class="clavinova-card__body"><p class="clavinova-card__series">Limited Availability</p><h2><?= htmlspecialchars($model) ?></h2><p class="clavinova-sale__finish"><?= htmlspecialchars($finish) ?></p><p class="clavinova-sale__regular">Regular price <del><?= htmlspecialchars($regular_price) ?></del></p><p class="clavinova-card__price"><span>Sale price · current MAP</span> <?= htmlspecialchars($map_price) ?></p><a class="clavinova-card__link" href="/contact-us/?model=<?= rawurlencode($model) ?>">Ask About This Piano</a></div></article>
		<?php endforeach; ?>
	</div><section class="clavinova-price-note"><h2>Call Before Visiting</h2><p>These are closeout instruments with limited quantities. Please call or text <a href="tel:+15703525501">570-352-5501</a> to confirm that your preferred finish is still available.</p></section></div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
