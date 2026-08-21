<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = ['title' => 'Clavinova Sale | In-Stock Yamaha Digital Pianos | Piano Depot', 'description' => 'Shop remaining in-stock Yamaha Clavinova and hybrid piano models at Piano Depot in Olyphant, PA. Limited quantities available.', 'extra_css' => ['/wp-content/uploads/piano-depot-category-pages.css', '/wp-content/uploads/clavinova-catalog.css']];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
$sale_models = [
	['Clavinova CLP-725', '$1,999.99 – $2,399.99', '/wp-content/uploads/2021/07/clp-725-Black-1.jpg', '/product/clavinova-clp-725/'],
	['Clavinova CLP-735', '$2,899.99 – $3,399.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg', '/product/clavinova-clp-735/'],
	['Clavinova CLP-745', '$3,799.99 – $4,299.99', '/wp-content/uploads/2021/06/mEpf7dj4.jpeg', '/product/clavinova-clp-735-copy/'],
	['Clavinova CLP-765GP', '$5,999.99 – $6,799.99', '/wp-content/uploads/2021/07/CLP-765gp-PE-300x300.jpg', '/product/clavinova-clp-765-gp/'],
	['Clavinova CLP-775', '$4,999.99 – $5,599.99', '/wp-content/uploads/2021/07/CLP-775-PE-300x300.jpg', '/product/clavinova-clp-775/'],
	['Clavinova CLP-785', '$6,399.99 – $8,199.99', '/wp-content/uploads/2021/07/CLP-785-PE-1.jpg', '/product/clavinova-clp-785/'],
	['Clavinova CLP-795GP', '$8,199.99 – $9,199.99', '/wp-content/uploads/2021/07/CLP-795-GP-PE-300x300.jpg', '/product/clavinova-clp-795-gp/'],
	['Clavinova CSP-150', '$3,799.99 – $4,299.99', '/wp-content/uploads/2021/07/CSP150PE-300x300.jpg', '/product/clavinova-csp-150/'],
	['Clavinova CSP-170', '$5,149.99 – $5,749.99', '/wp-content/uploads/2021/07/CSP170PE-300x300.jpg', '/product/clavinova-csp-170/'],
	['Clavinova CVP-701', '$4,299.99 – $5,149.99', '/wp-content/uploads/2021/07/CVP701PE-300x300.jpg', '/product/clavinova-cvp-701/'],
	['Clavinova CVP-805', '$7,999.99 – $8,699.99', '/wp-content/uploads/2021/07/CVP-805-PE-300x300.jpg', '/product/clavinova-cvp-805/'],
	['Yamaha NU1X', '$7,399.99 – $7,599.99', '/wp-content/uploads/2021/09/NU1X-black-front-300x300.jpg', '/product/nu1x/'],
];
?>
<main id="main" class="site-main clr clavinova-catalog clavinova-sale" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<section class="clavinova-hero clavinova-hero--sale"><p class="clavinova-kicker">Limited In-Stock Models</p><h1>Clavinova Sale</h1><p>Yamaha’s catalog has changed, but Piano Depot still has select previous-generation Clavinova and hybrid models available. Quantities and finishes are limited.</p><a class="clavinova-button" href="/contact-us/">Check Current Availability</a></section>
	<div class="clavinova-catalog__content"><div class="clavinova-grid">
		<?php foreach ($sale_models as [$model, $price, $image, $link]) : ?>
		<article class="clavinova-card"><a class="clavinova-card__image" href="<?= htmlspecialchars($link) ?>"><img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($model) ?>"></a><div class="clavinova-card__body"><p class="clavinova-card__series">Limited Availability</p><h2><?= htmlspecialchars($model) ?></h2><p class="clavinova-card__price"><span>Advertised MAP</span> <?= htmlspecialchars($price) ?></p><a class="clavinova-card__link" href="/contact-us/?model=<?= rawurlencode($model) ?>">Ask About This Piano</a></div></article>
		<?php endforeach; ?>
	</div><section class="clavinova-price-note"><h2>Call Before Visiting</h2><p>Inventory is limited and may change quickly. Please call or text <a href="tel:+15703525501">570-352-5501</a> to confirm the model and finish you want to see.</p></section></div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
