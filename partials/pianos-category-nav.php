<?php
$currentNav = (isset($catalog) && is_array($catalog)) ? ($catalog['nav'] ?? '') : '';
$navLinks = [
    '/piano-closeout-sales-in-olyphant-pa/' => 'Closeout Sales',
    '/disklavier-pianos/' => 'Disklavier',
    '/acoustic-grand-pianos/' => 'Grand Pianos',
    '/acoustic-silent-trans-acoustic-pianos/' => 'Silent & TransAcoustic',
    '/acoustic-upright-pianos/' => 'Upright Pianos',
    '/clavinova-and-hybrid-pianos/' => 'Clavinova & Hybrid',
    '/clavinova-sale/' => 'Clavinova Sale',
    '/portable-digital-pianos/' => 'Portable Digital',
    '/workstation-keyboards/' => 'Workstations',
    '/used-and-refurbished/' => 'Used & Refurbished',
];
?>
<section class="piano-category-nav" aria-labelledby="piano-category-nav-title">
	<div class="piano-category-nav__inner">
		<p class="piano-category-nav__eyebrow">Piano Depot Collection</p>
		<h2 id="piano-category-nav-title">Explore Our Pianos &amp; Keyboards</h2>
		<nav aria-label="Piano and keyboard categories">
			<?php foreach ($navLinks as $href => $label) :
				$current = $href === $currentNav;
			?>
			<a href="<?= htmlspecialchars($href) ?>" class="<?= $current ? 'piano-category-nav__current' : '' ?>"<?= $current ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($label) ?></a>
			<?php endforeach; ?>
		</nav>
	</div>
</section>
