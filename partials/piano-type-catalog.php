<?php
/** @var array $catalog */
$cfg = pd_config();
$ctaTitle = $catalog['cta_title'] ?? 'See these pianos in Olyphant';
$ctaText = $catalog['cta_text'] ?? 'Call or text ahead for an appointment. Ask for Frank Bissol.';
$ctaAction = $catalog['cta_action'] ?? 'Schedule an Appointment';
$groups = $catalog['groups'] ?? [];
$models = $catalog['models'] ?? [];
$videos = $catalog['videos'] ?? [];
$gallery = $catalog['gallery'] ?? [];

if (!function_exists('pd_type_card')) {
function pd_type_card(array $model): void
{
	$name = $model['name'] ?? '';
	$href = $model['href'] ?? '#';
	$image = $model['image'] ?? '';
	$description = $model['description'] ?? '';
	$label = $model['label'] ?? '';
	?>
	<article class="piano-type-card">
		<a class="piano-type-card__image" href="<?= htmlspecialchars($href) ?>">
			<?php if ($image !== '') : ?>
			<img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>">
			<?php endif; ?>
		</a>
		<div class="piano-type-card__body">
			<?php if ($label !== '') : ?><p class="piano-type-card__label"><?= htmlspecialchars($label) ?></p><?php endif; ?>
			<h3><?= htmlspecialchars($name) ?></h3>
			<?php if ($description !== '') : ?><p><?= htmlspecialchars($description) ?></p><?php endif; ?>
			<a class="piano-type-card__link" href="<?= htmlspecialchars($href) ?>">See this piano</a>
		</div>
	</article>
	<?php
}
}
?>
<main id="main" class="site-main clr piano-type-catalog" role="main">
	<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/pianos-category-nav.php'; ?>
	<header class="piano-type-hero" style="background-image: url('<?= htmlspecialchars($catalog['hero_image']) ?>');">
		<div class="piano-type-hero__inner">
			<p class="piano-type-hero__eyebrow"><?= htmlspecialchars($catalog['eyebrow']) ?></p>
			<h1><?= htmlspecialchars($catalog['title']) ?></h1>
			<p><?= htmlspecialchars($catalog['intro']) ?></p>
			<div class="piano-type-hero__actions">
				<a class="piano-type-button" href="/contact-us/"><?= htmlspecialchars($ctaAction) ?></a>
				<?php if (!empty($catalog['yamaha_href'])) : ?>
				<a class="piano-type-button piano-type-button--light" href="<?= htmlspecialchars($catalog['yamaha_href']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($catalog['yamaha_label'] ?? 'View catalog on Yamaha’s website') ?></a>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<div class="piano-type-content">
		<?php if ($groups !== []) : ?>
		<nav class="piano-type-groups" aria-label="Used piano categories">
			<?php foreach ($groups as $group) : ?>
			<a href="#<?= htmlspecialchars($group['id']) ?>"><?= htmlspecialchars($group['title']) ?></a>
			<?php endforeach; ?>
		</nav>
		<?php foreach ($groups as $group) : ?>
		<section class="piano-type-group" id="<?= htmlspecialchars($group['id']) ?>">
			<h2><?= htmlspecialchars($group['title']) ?></h2>
			<?php if (!empty($group['href'])) : ?><p><a href="<?= htmlspecialchars($group['href']) ?>">Browse this category</a></p><?php endif; ?>
			<?php if (!empty($group['models'])) : ?>
			<div class="piano-type-grid">
				<?php foreach ($group['models'] as $model) { pd_type_card($model); } ?>
			</div>
			<?php endif; ?>
		</section>
		<?php endforeach; ?>
		<?php elseif ($models !== []) : ?>
		<div class="piano-type-grid">
			<?php foreach ($models as $model) { pd_type_card($model); } ?>
		</div>
		<?php endif; ?>

		<?php if ($gallery !== []) : ?>
		<div class="piano-type-gallery" aria-label="Showroom and warehouse pianos">
			<?php foreach ($gallery as $photo) : ?>
			<img src="<?= htmlspecialchars($photo['src']) ?>" alt="<?= htmlspecialchars($photo['alt'] ?? '') ?>">
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php
		$playable = [];
		foreach ($videos as $video) {
			if (!empty($video['id'])) {
				$playable[] = $video;
			}
		}
		if ($playable !== []) :
		?>
		<section class="piano-type-videos">
			<h2>Watch these pianos</h2>
			<div class="piano-type-videos__grid">
				<?php foreach ($playable as $video) : ?>
				<figure class="piano-type-video">
					<iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video['id']) ?>" title="<?= htmlspecialchars($video['caption'] ?? $catalog['title']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					<?php if (!empty($video['caption'])) : ?><p><?= htmlspecialchars($video['caption']) ?></p><?php endif; ?>
				</figure>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endif; ?>

		<section class="piano-type-cta">
			<div class="piano-type-cta__copy">
				<h2><?= htmlspecialchars($ctaTitle) ?></h2>
				<p><?= htmlspecialchars($ctaText) ?> Call or text <a class="piano-type-cta__phone" href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>"><?= htmlspecialchars($cfg['phone']) ?></a>.</p>
			</div>
			<a class="piano-type-button" href="/contact-us/"><?= htmlspecialchars($ctaAction) ?></a>
		</section>
	</div>
</main>
