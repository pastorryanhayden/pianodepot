<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = ['title' => 'Clavinova Sale | In-Stock Yamaha Digital Pianos | Piano Depot', 'description' => 'Shop remaining in-stock Yamaha Clavinova and hybrid piano models at Piano Depot in Olyphant, PA. Limited quantities available.', 'extra_css' => ['/wp-content/uploads/piano-depot-category-pages.css', '/wp-content/uploads/clavinova-catalog.css']];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
$sale_models = [
	['CLP-725B', 'Matte Black', '$2,299.00', '$1,999.99', '/wp-content/uploads/2021/07/clp-725-Black-1.jpg'],
	['CLP-725R', 'Dark Rosewood', '$2,299.00', '$1,999.99', '/wp-content/uploads/2021/07/clp-725-Black-1.jpg'],
	['CLP-725PE', 'Polished Ebony', '$2,699.00', '$2,399.99', '/wp-content/uploads/2021/07/clp-725-Black-1.jpg'],
	['CLP-735B', 'Matte Black', '$3,199.00', '$2,899.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg'],
	['CLP-735PE', 'Polished Ebony', '$3,699.00', '$3,399.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg'],
	['CLP-735R', 'Dark Rosewood', '$3,199.00', '$2,899.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg'],
	['CLP-735WH', 'Matte White', '$3,199.00', '$2,899.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg'],
	['CVP-805PE', 'Polished Ebony', '', '$8,699.99', '/wp-content/uploads/2021/07/CVP-805-PE-300x300.jpg'],
	['CLP-825R', 'Dark Rosewood', '$2,249.99', '$2,099.99', '/wp-content/uploads/2026/08/clavinova-current/clp-825.jpg'],
	['CLP-835R', 'Dark Rosewood', '$3,299.99', '$3,199.99', '/wp-content/uploads/2026/08/clavinova-current/clp-835.jpg'],
	['CLP-865GP', 'Polished Ebony', '$6,799.99', '$6,499.99', '/wp-content/uploads/2026/08/clavinova-current/clp-865gp.jpg'],
	['CLP-875B', 'Matte Black', '$5,699.99', '$5,299.99', '/wp-content/uploads/2026/08/clavinova-current/clp-875.jpg'],
	['CLP-875R', 'Dark Rosewood', '$5,699.99', '$5,299.99', '/wp-content/uploads/2026/08/clavinova-current/clp-875.jpg'],
	['CLP-875PE', 'Polished Ebony', '$6,399.99', '$5,899.99', '/wp-content/uploads/2026/08/clavinova-current/clp-875.jpg'],
	['NU1XPE', 'Polished Ebony', '', '$7,599.99', '/wp-content/uploads/2021/09/NU1X-black-front-300x300.jpg'],
	['CSP-255B', 'Matte Black', '$4,199.99', '$3,999.99', '/wp-content/uploads/2026/08/clavinova-current/csp-255.jpg'],
	['CVP-905B', 'Matte Black', '$8,999.99', '$8,499.99', '/wp-content/uploads/2026/08/clavinova-current/cvp-905.jpg'],
];
?>
<main id="main" class="site-main clr clavinova-catalog clavinova-sale" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<section class="clavinova-hero clavinova-hero--sale"><p class="clavinova-kicker">Limited In-Stock Models</p><h1>Clavinova Inventory Sale</h1><p>Save on Piano Depot’s remaining Yamaha Clavinova and hybrid inventory. Every advertised sale price follows Yamaha’s current minimum advertised price. Quantities and finishes are limited.</p><a class="clavinova-button" href="/contact-us/">Check Current Availability</a></section>
	<div class="clavinova-catalog__content"><div class="clavinova-grid">
		<?php foreach ($sale_models as [$model, $finish, $regular_price, $map_price, $image]) : ?>
		<article class="clavinova-card"><div class="clavinova-card__image"><img src="<?= htmlspecialchars($image) ?>" alt="Yamaha <?= htmlspecialchars($model) ?> in <?= htmlspecialchars($finish) ?>"></div><div class="clavinova-card__body"><p class="clavinova-card__series">In Stock · Limited Availability</p><h2><?= htmlspecialchars($model) ?></h2><p class="clavinova-sale__finish"><?= htmlspecialchars($finish) ?></p><?php if ($regular_price !== '') : ?><p class="clavinova-sale__regular">Previous MAP <del><?= htmlspecialchars($regular_price) ?></del></p><?php endif; ?><p class="clavinova-card__price"><span>Sale price · current MAP</span> <?= htmlspecialchars($map_price) ?></p><a class="clavinova-card__link" href="/contact-us/?model=<?= rawurlencode($model) ?>">Ask About This Piano</a></div></article>
		<?php endforeach; ?>
	</div><section class="clavinova-price-note"><h2>Call Before Visiting</h2><p>These are closeout instruments with limited quantities. Please call or text <a href="tel:+15703525501">570-352-5501</a> to confirm that your preferred finish is still available.</p></section></div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
