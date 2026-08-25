<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Apply for Credit | Piano Depot in Olyphant, PA',
    'description' => 'Purchase a piano on credit through Piano Depot. We accept major credit cards and offer financing, including promotional rates for qualifying customers.',
    'extra_css' => [
        '/wp-content/uploads/section-landing-pages.css',
    ],
];
$cfg = pd_config();
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr section-landing" role="main">
	<header class="section-landing__hero">
		<div class="section-landing__hero-inner">
			<p class="section-landing__eyebrow">Helpful Information</p>
			<h1>Apply for Credit</h1>
			<p>We take all major credit cards and offer financing. Qualifying customers may receive promotional rates. Apply below, or call if you have questions.</p>
		</div>
	</header>
	<div class="section-landing__content">
		<section class="section-landing__lead">
			<h2>To apply for credit, follow these directions</h2>
			<p>Complete the pre-qualification form. Piano Depot will follow up with the best promotional rate available for you. Call or text <a href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>"><?= htmlspecialchars($cfg['phone']) ?></a> and ask for Frank Bissol if you need help.</p>
		</section>
		<section class="section-embed" aria-label="Credit pre-qualification application">
			<iframe src="https://simplefinancing.umwsb.com/embed/prequal-loan-application?id=dd13534d-a6f1-4b1e-a37f-d2c7993305c2" name="PCCapplication" title="Apply for piano financing" loading="lazy"></iframe>
		</section>
		<section class="section-landing__cta">
			<div>
				<h2>Questions about financing?</h2>
				<p>Call or text ahead. Ask for Frank Bissol.</p>
			</div>
			<a href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>">Call <?= htmlspecialchars($cfg['phone']) ?></a>
		</section>
	</div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
