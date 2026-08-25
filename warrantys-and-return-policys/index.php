<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Warranties and Return Policies | Piano Depot',
    'description' => 'Yamaha new-piano warranties and Piano Depot’s 30-day return policy. Read the details and contact us with questions.',
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
			<h1>Warranties and Return Policies</h1>
			<p>New Yamaha pianos carry the manufacturer’s written warranty. Used pianos are sold as inspected and refurbished instruments. Returns follow the policy below.</p>
		</div>
	</header>
	<div class="section-landing__content">
		<section class="section-landing__lead">
			<h2>New piano warranties</h2>
			<p>These Yamaha warranty PDFs apply to new pianos, not used pianos. Open the document for the instrument you are considering.</p>
		</section>
		<section class="section-landing__grid section-landing__grid--two" aria-label="Yamaha warranty documents">
			<article class="section-card">
				<p class="section-card__label">PDF</p>
				<h2>Disklavier, TransAcoustic &amp; Silent</h2>
				<p>Manufacturer warranty for Disklavier, TransAcoustic, and Silent series pianos.</p>
				<a href="https://usa.yamaha.com/files/ocp/en_us/support/warranty/pianos/Disklavier_n_Silent_Piano_warranty.pdf" target="_blank" rel="noopener">View warranty PDF <span aria-hidden="true">→</span></a>
			</article>
			<article class="section-card">
				<p class="section-card__label">PDF</p>
				<h2>Acoustic Pianos</h2>
				<p>Manufacturer warranty for Yamaha acoustic pianos.</p>
				<a href="https://usa.yamaha.com/files/ocp/en_us/support/warranty/pianos/070109_Acoustic_Piano_Warranty.pdf" target="_blank" rel="noopener">View warranty PDF <span aria-hidden="true">→</span></a>
			</article>
			<article class="section-card">
				<p class="section-card__label">PDF</p>
				<h2>Hybrid Pianos</h2>
				<p>Manufacturer warranty for Yamaha hybrid pianos.</p>
				<a href="https://usa.yamaha.com/files/ocp/en_us/support/warranty/pianos/KEYBOARD_Hybrid_Piano_Warranty_2013.pdf" target="_blank" rel="noopener">View warranty PDF <span aria-hidden="true">→</span></a>
			</article>
			<article class="section-card">
				<p class="section-card__label">PDF</p>
				<h2>Clavinova Digital Pianos</h2>
				<p>Manufacturer warranty for Yamaha Clavinova digital pianos.</p>
				<a href="https://usa.yamaha.com/files/WARRANTY_DIVISION_Keyboard_Clavinova_2017_LOGO_CHG_6da0529e3db7a2dd2599893ee20cbfcb.pdf" target="_blank" rel="noopener">View warranty PDF <span aria-hidden="true">→</span></a>
			</article>
		</section>
		<section class="section-policy" aria-labelledby="return-policy-title">
			<h2 id="return-policy-title">Return policy</h2>
			<h3>Piano Depot’s 100% Assurance – Satisfaction Guarantee</h3>
			<p>We want you to be completely happy with your purchase. If you were not satisfied with your new piano or digital piano that was purchased from us online, for any reason, you may return it for a refund of the purchase price, an in-house credit, or you may exchange for another product, all within 30 days from the shipping date. Return shipping costs are the responsibility of the buyer.</p>
			<p>If you believe the product is defective, please contact us so we can verify the problem and to help if a return is needed within 30 days, no later.</p>
			<p>To qualify for a return and get authorization, you must email us at <a href="mailto:info@pianodepot.com">info@pianodepot.com</a> within 30 days of the <strong>shipping date</strong>.</p>
			<p>We must receive your piano in the manufacturer’s original package along with all accessories, manuals, headphones, etc. Returns that show wear, abuse, or neglect, may be refused and/or could be subject to a restocking fee (typically 20%). We expect each return to be in “showroom new condition”.</p>
			<p>Please ship your return with proper insurance and be sure to pack it securely. Damage that occurs in shipping will not be our responsibility. Lastly, please make sure the shipment has tracking, for your records and ours.</p>
			<p>Refunds will typically be issued by the same method of the original payment.</p>
			<p>Have any questions, please call us at <a href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>"><?= htmlspecialchars($cfg['phone']) ?></a>.</p>
			<p class="section-policy__note"><strong>Important — special orders cannot be returned.</strong></p>
		</section>
		<section class="section-landing__cta">
			<div>
				<h2>Questions about a warranty or return?</h2>
				<p>Call or email Piano Depot and we will walk you through the next step.</p>
			</div>
			<a href="/contact-us/">Contact Us</a>
		</section>
	</div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
