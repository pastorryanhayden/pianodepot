<main id="main" class="site-main clr section-landing" role="main">
	<header class="section-landing__hero">
		<div class="section-landing__hero-inner">
			<p class="section-landing__eyebrow"><?= htmlspecialchars($landing['eyebrow']) ?></p>
			<h1><?= htmlspecialchars($landing['title']) ?></h1>
			<p><?= htmlspecialchars($landing['intro']) ?></p>
		</div>
	</header>
	<div class="section-landing__content">
		<?php if (!empty($landing['lead_title'])) : ?>
		<section class="section-landing__lead">
			<h2><?= htmlspecialchars($landing['lead_title']) ?></h2>
			<p><?= htmlspecialchars($landing['lead']) ?></p>
		</section>
		<?php endif; ?>
		<section class="section-landing__grid" aria-label="<?= htmlspecialchars($landing['title']) ?> links">
			<?php foreach ($landing['cards'] as $card) : ?>
			<article class="section-card">
				<p class="section-card__label"><?= htmlspecialchars($card['label']) ?></p>
				<h2><?= htmlspecialchars($card['title']) ?></h2>
				<p><?= htmlspecialchars($card['description']) ?></p>
				<a href="<?= htmlspecialchars($card['href']) ?>"<?= !empty($card['external']) ? ' target="_blank" rel="noopener"' : '' ?>><?= htmlspecialchars($card['action']) ?> <span aria-hidden="true">→</span></a>
			</article>
			<?php endforeach; ?>
		</section>
		<section class="section-landing__cta">
			<div><h2><?= htmlspecialchars($landing['cta_title']) ?></h2><p><?= htmlspecialchars($landing['cta_text']) ?></p></div>
			<a href="<?= htmlspecialchars($landing['cta_href']) ?>"><?= htmlspecialchars($landing['cta_action']) ?></a>
		</section>
	</div>
</main>
