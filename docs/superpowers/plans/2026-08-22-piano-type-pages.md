# Piano Type Pages Catalog Rebuild Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the seven leftover piano-type pages as Clavinova-style catalogs with a shared partial, restyled videos, and on-disk rollback copies.

**Architecture:** Each type `index.php` owns a `$catalog` array and includes `partials/piano-type-catalog.php`. Shared CSS is `wp-content/uploads/piano-type-pages.css`. Current WordPress markup is copied to `index.legacy.php` in the same folder before the public page is replaced.

**Tech Stack:** Plain PHP includes, existing `php tests/run.php` helpers, existing site header/footer, no Composer, no database.

## Global Constraints

- No database. No Laravel. No Composer. No PHPUnit.
- Includes use `$_SERVER['DOCUMENT_ROOT'] . '/partials/...'`.
- Do not invent MAP prices or models that are not already on the page.
- Do not write new marketing copy; de-duplicate existing type-page text.
- Yamaha secondary CTA only when the URL is actually for that type (Disklavier is the one confirmed match).
- Drop Revolution Slider, Quick View, WooCommerce search, duplicate black nav, sidebar Gravity Form, expired 0% APR promo.
- Appointment CTA is `/contact-us/`. Phone/text `570-352-5501`.
- Do not change Clavinova, Clavinova Sale, Closeout, or `/product/…` pages.
- Do not push `origin main` unless the user explicitly asks.
- Tests: `php tests/run.php`. Local preview: `http://localhost:8006/`.

---

## File structure

| Path | Responsibility |
| --- | --- |
| `tests/test_piano_type_pages.php` | File + optional HTTP checks for the seven type pages |
| `{type}/index.legacy.php` | Unlinked rollback copy of the current WordPress page |
| `wp-content/uploads/piano-type-pages.css` | Hero, cards, videos, CTA, collection-nav current chip |
| `partials/pianos-category-nav.php` | Collection switcher; marks current type |
| `partials/piano-type-catalog.php` | Only renderer for type catalogs |
| `disklavier-pianos/index.php` | First rebuilt public page |
| `acoustic-grand-pianos/index.php` | Grand catalog |
| `acoustic-silent-trans-acoustic-pianos/index.php` | Silent/TransAcoustic catalog |
| `acoustic-upright-pianos/index.php` | Upright catalog |
| `portable-digital-pianos/index.php` | Portable catalog |
| `workstation-keyboards/index.php` | Workstation catalog |
| `used-and-refurbished/index.php` | Used catalog with groups + gallery |

`$catalog` shape produced by each public page and consumed by the partial:

```php
$catalog = [
    'nav' => '/disklavier-pianos/',          // required; matches a collection-nav href
    'eyebrow' => 'Yamaha Disklavier',        // required
    'title' => 'Disklavier Pianos',          // required
    'intro' => '…',                          // required
    'hero_image' => '/wp-content/uploads/…', // required
    'yamaha_href' => 'https://…',            // optional
    'yamaha_label' => 'View catalog on Yamaha’s website', // optional
    'models' => [                            // required unless groups is set
        [
            'name' => 'Enspire CL',
            'href' => '/product/enspire-cl/',
            'image' => '/wp-content/uploads/…',
            'description' => '…',
            'label' => 'Disklavier',
        ],
    ],
    'groups' => [                            // optional; used page only
        [
            'id' => 'used-grands',
            'title' => 'Restored Grand Pianos',
            'href' => '/used-refurbished-grand-pianos/',
            'models' => [ /* same model fields */ ],
        ],
    ],
    'videos' => [                            // optional
        ['id' => 'T9Qv5B1Eb-k', 'caption' => '…'],
    ],
    'gallery' => [                           // optional; used warehouse photos
        ['src' => '/wp-content/uploads/…', 'alt' => 'Used pianos at Piano Depot'],
    ],
    'cta_title' => 'See these pianos in Olyphant',
    'cta_text' => 'Call or text ahead for an appointment. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
```

---

### Task 1: Failing type-page tests

**Files:**
- Create: `tests/test_piano_type_pages.php`

**Interfaces:**
- Consumes: `expect()` from `tests/helpers.php`; `tests/run.php` auto-loads `test_*.php`
- Produces: assertions that later tasks must satisfy (legacy files exist; public pages are catalogs)

- [ ] **Step 1: Write the failing test**

Create `tests/test_piano_type_pages.php`:

```php
<?php

$root = dirname(__DIR__);

$types = [
    'disklavier-pianos' => 'Disklavier Pianos',
    'acoustic-grand-pianos' => 'Acoustic Grand Pianos',
    'acoustic-silent-trans-acoustic-pianos' => 'Silent & TransAcoustic Pianos',
    'acoustic-upright-pianos' => 'Acoustic Upright Pianos',
    'portable-digital-pianos' => 'Portable Digital Pianos',
    'workstation-keyboards' => 'Workstation Keyboards',
    'used-and-refurbished' => 'Used & Refurbished Pianos',
];

$forbidden = [
    'rs-module-wrap',
    'owp-quick-view',
    'wc-block-product-search',
    'piano_internal-links',
];

$disklavierVideos = ['T9Qv5B1Eb-k', 'U1cNpWSI9Nw', 'Xu36GOKXs5M', 'kvUFUKFUDC4', 'hlvBms8IW7o'];

foreach ($types as $slug => $title) {
    $legacy = $root . '/' . $slug . '/index.legacy.php';
    $page = $root . '/' . $slug . '/index.php';
    $html = file_get_contents($page);

    expect(is_file($legacy), $slug . ' has index.legacy.php');
    expect(str_contains($html, "partials/piano-type-catalog.php"), $slug . ' uses catalog partial');
    expect(str_contains($html, '/wp-content/uploads/piano-type-pages.css'), $slug . ' loads type-page CSS');
    expect(str_contains($html, $title), $slug . ' has hero title');
    expect(str_contains($html, '/contact-us/'), $slug . ' has appointment CTA');

    foreach ($forbidden as $needle) {
        expect(!str_contains($html, $needle), $slug . ' does not contain ' . $needle);
    }
}

$nav = file_get_contents($root . '/partials/pianos-category-nav.php');
expect(str_contains($nav, 'piano-category-nav__current'), 'collection nav can mark the current type');

$partial = file_get_contents($root . '/partials/piano-type-catalog.php');
expect(is_file($root . '/partials/piano-type-catalog.php'), 'catalog partial exists');
expect(str_contains($partial, 'piano-type-hero'), 'partial renders photo hero');
expect(str_contains($partial, 'youtube.com/embed/'), 'partial can render videos');

$disk = file_get_contents($root . '/disklavier-pianos/index.php');
foreach ($disklavierVideos as $id) {
    expect(substr_count($disk, $id) === 1, 'Disklavier includes ' . $id . ' once');
}

$http = null;
foreach ([8006, 8003] as $port) {
    $probe = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.3);
    if ($probe === false) {
        continue;
    }
    fclose($probe);
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true, 'follow_location' => 0]]);
    $body = @file_get_contents('http://127.0.0.1:' . $port . '/disklavier-pianos/', false, $ctx);
    $code = 0;
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
        $code = (int) $m[1];
    }
    $http = ['code' => $code, 'body' => $body === false ? '' : $body];
    break;
}

if ($http === null) {
    echo "SKIP HTTP: start php -S localhost:8006 router.php\n";
} else {
    expect($http['code'] === 200, 'GET /disklavier-pianos/ is 200');
    expect(str_contains($http['body'], 'Disklavier Pianos'), 'HTTP Disklavier has title');
    expect(str_contains($http['body'], 'Schedule an Appointment'), 'HTTP Disklavier has appointment CTA');
    expect(!str_contains($http['body'], 'rs-module-wrap'), 'HTTP Disklavier has no slider');
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL lines for missing `index.legacy.php`, missing catalog partial, and public pages still containing `rs-module-wrap` / `owp-quick-view`.

- [ ] **Step 3: Do not implement pages yet**

Leave the public type pages unchanged. This task only adds tests.

- [ ] **Step 4: Commit**

```bash
git add tests/test_piano_type_pages.php
git commit -m "Add failing tests for piano type catalog pages"
```

---

### Task 2: Save rollback copies

**Files:**
- Create: `disklavier-pianos/index.legacy.php`
- Create: `acoustic-grand-pianos/index.legacy.php`
- Create: `acoustic-silent-trans-acoustic-pianos/index.legacy.php`
- Create: `acoustic-upright-pianos/index.legacy.php`
- Create: `portable-digital-pianos/index.legacy.php`
- Create: `workstation-keyboards/index.legacy.php`
- Create: `used-and-refurbished/index.legacy.php`

**Interfaces:**
- Consumes: current public `index.php` bytes
- Produces: unlinked rollback files; `is_file(index.legacy.php)` becomes true

- [ ] **Step 1: Copy each public page**

From the repository root:

```bash
cp disklavier-pianos/index.php disklavier-pianos/index.legacy.php
cp acoustic-grand-pianos/index.php acoustic-grand-pianos/index.legacy.php
cp acoustic-silent-trans-acoustic-pianos/index.php acoustic-silent-trans-acoustic-pianos/index.legacy.php
cp acoustic-upright-pianos/index.php acoustic-upright-pianos/index.legacy.php
cp portable-digital-pianos/index.php portable-digital-pianos/index.legacy.php
cp workstation-keyboards/index.php workstation-keyboards/index.legacy.php
cp used-and-refurbished/index.php used-and-refurbished/index.legacy.php
```

Do not add the legacy files to the nav. Do not change the public `index.php` files in this task.

- [ ] **Step 2: Re-run tests**

Run: `php tests/run.php`

Expected: PASS for each `$slug has index.legacy.php`. Still FAIL for catalog partial / forbidden markup.

- [ ] **Step 3: Commit**

```bash
git add disklavier-pianos/index.legacy.php acoustic-grand-pianos/index.legacy.php acoustic-silent-trans-acoustic-pianos/index.legacy.php acoustic-upright-pianos/index.legacy.php portable-digital-pianos/index.legacy.php workstation-keyboards/index.legacy.php used-and-refurbished/index.legacy.php
git commit -m "Save legacy copies of piano type pages"
```

---

### Task 3: Shared CSS, current-nav, and catalog partial

**Files:**
- Create: `wp-content/uploads/piano-type-pages.css`
- Modify: `partials/pianos-category-nav.php`
- Create: `partials/piano-type-catalog.php`

**Interfaces:**
- Consumes: `$catalog` array defined in Task file-structure; `$page` already loaded by header
- Produces: `partials/piano-type-catalog.php` renderer; `.piano-category-nav__current` class

- [ ] **Step 1: Mark the current collection chip**

Replace `partials/pianos-category-nav.php` with:

```php
<?php
$currentNav = $catalog['nav'] ?? '';
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
```

Clavinova pages include this partial without `$catalog`. `$catalog['nav'] ?? ''` keeps those pages working; no chip is current there.

- [ ] **Step 2: Write the type-page CSS**

Create `wp-content/uploads/piano-type-pages.css`:

```css
.piano-type-catalog { background: #fff; color: #333; }
.piano-category-nav { padding: 132px 22px 0; background: #fff; }
.piano-category-nav__inner { max-width: 1160px; margin: 0 auto; padding: 30px 34px 32px; box-sizing: border-box; background: #f6f3ed; border-left: 5px solid #b11f24; }
.piano-category-nav__eyebrow { margin: 0 0 5px; color: #b11f24; font-size: 12px; font-weight: 800; letter-spacing: .11em; text-transform: uppercase; }
.piano-category-nav h2 { margin: 0 0 18px; color: #222; font-size: 29px; line-height: 1.25; }
.piano-category-nav nav { display: flex; flex-wrap: wrap; gap: 9px; }
.piano-category-nav nav a { display: inline-block; padding: 9px 13px; color: #333; background: #fff; border: 1px solid #d8d3ca; font-size: 13px; font-weight: 700; line-height: 1.25; text-decoration: none; }
.piano-category-nav nav a:hover, .piano-category-nav nav a:focus, .piano-category-nav nav a.piano-category-nav__current { color: #fff; background: #b11f24; border-color: #b11f24; }
.piano-type-hero { position: relative; max-width: 1160px; margin: 28px auto 36px; min-height: 420px; overflow: hidden; color: #fff; background: #171717 center / cover no-repeat; }
.piano-type-hero::before { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(17,17,17,.82) 0%, rgba(17,17,17,.45) 55%, rgba(177,31,36,.28) 100%); }
.piano-type-hero__inner { position: relative; z-index: 1; max-width: 720px; padding: 54px 48px; }
.piano-type-hero__eyebrow { margin: 0 0 8px; color: #f0c7c9; font-size: 13px; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
.piano-type-hero h1 { margin: 0 0 16px; color: #fff; font-size: clamp(42px, 6vw, 68px); line-height: 1.05; }
.piano-type-hero p { max-width: 640px; margin: 0 0 24px; color: #f3f3f3; font-size: 18px; line-height: 1.65; }
.piano-type-hero__actions { display: flex; flex-wrap: wrap; gap: 12px; }
.piano-type-button { display: inline-block; padding: 13px 20px; color: #fff; background: #b11f24; font-weight: 800; text-decoration: none; }
.piano-type-button:hover, .piano-type-button:focus { color: #fff; background: #94191e; }
.piano-type-button--light { color: #222; background: #fff; }
.piano-type-button--light:hover, .piano-type-button--light:focus { color: #fff; background: #b11f24; }
.piano-type-content { max-width: 1160px; margin: 0 auto; padding: 0 22px 64px; box-sizing: border-box; }
.piano-type-groups { display: flex; flex-wrap: wrap; gap: 10px; margin: 0 0 36px; }
.piano-type-groups a { flex: 1 1 180px; padding: 15px 18px; color: #222; background: #f6f3ed; border-bottom: 3px solid #b11f24; font-weight: 800; text-align: center; text-decoration: none; }
.piano-type-group { margin: 0 0 48px; scroll-margin-top: 120px; }
.piano-type-group h2 { margin: 0 0 8px; color: #222; font-size: 32px; }
.piano-type-group > p { margin: 0 0 22px; color: #555; }
.piano-type-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; }
.piano-type-card { display: flex; flex-direction: column; min-width: 0; background: #fff; border: 1px solid #e1e1e1; box-shadow: 0 4px 16px rgba(0,0,0,.09); }
.piano-type-card__image { display: flex; align-items: center; justify-content: center; height: 280px; padding: 14px; box-sizing: border-box; background: #f3f3f3; }
.piano-type-card__image img { display: block; width: 100%; height: 100%; object-fit: contain; }
.piano-type-card__body { display: flex; flex: 1; flex-direction: column; padding: 22px; }
.piano-type-card__label { margin: 0 0 6px; color: #b11f24; font-size: 12px; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; }
.piano-type-card h3 { margin: 0 0 10px; color: #222; font-size: 23px; line-height: 1.25; }
.piano-type-card__body p { margin: 0 0 18px; color: #555; line-height: 1.6; }
.piano-type-card__link { margin-top: auto; display: block; padding: 11px 14px; color: #fff; background: #b11f24; font-weight: 800; text-align: center; text-decoration: none; }
.piano-type-videos { margin: 12px 0 48px; }
.piano-type-videos h2 { margin: 0 0 18px; color: #222; font-size: 32px; }
.piano-type-videos__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; }
.piano-type-video iframe { display: block; width: 100%; aspect-ratio: 16 / 9; border: 0; background: #111; }
.piano-type-video p { margin: 10px 0 0; color: #444; font-size: 15px; line-height: 1.45; }
.piano-type-gallery { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin: 0 0 40px; }
.piano-type-gallery img { display: block; width: 100%; height: 160px; object-fit: cover; }
.piano-type-cta { display: flex; align-items: center; justify-content: space-between; gap: 28px; margin-top: 12px; padding: 34px 38px; color: #fff; background: #252525; }
.piano-type-cta h2 { margin: 0 0 6px; color: #fff; font-size: 29px; }
.piano-type-cta p { margin: 0; color: #e2e2e2; }
.piano-type-cta a { flex: 0 0 auto; padding: 13px 20px; color: #fff; background: #b11f24; font-weight: 800; text-decoration: none; }
@media (max-width: 900px) {
	.piano-type-grid, .piano-type-videos__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	.piano-type-gallery { grid-template-columns: repeat(2, minmax(0, 1fr)); }
	.piano-type-hero, .piano-type-content { margin-right: 18px; margin-left: 18px; }
}
@media (max-width: 600px) {
	.piano-category-nav { padding: 112px 18px 0; }
	.piano-type-hero { min-height: 0; }
	.piano-type-hero__inner { padding: 36px 24px; }
	.piano-type-hero h1 { font-size: 42px; }
	.piano-type-grid, .piano-type-videos__grid, .piano-type-gallery { grid-template-columns: 1fr; }
	.piano-type-cta { flex-direction: column; align-items: flex-start; padding: 28px 24px; }
}
```

- [ ] **Step 3: Write the catalog partial**

Create `partials/piano-type-catalog.php`:

```php
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
			<div>
				<h2><?= htmlspecialchars($ctaTitle) ?></h2>
				<p><?= htmlspecialchars($ctaText) ?> Call or text <a href="tel:<?= htmlspecialchars($cfg['phone_tel']) ?>"><?= htmlspecialchars($cfg['phone']) ?></a>.</p>
			</div>
			<a href="/contact-us/"><?= htmlspecialchars($ctaAction) ?></a>
		</section>
	</div>
</main>
```

`pd_type_card` is declared in this partial. If a later request includes the partial twice, PHP will fatal. These pages include it once.

- [ ] **Step 4: Run tests**

Run: `php tests/run.php`

Expected: PASS for collection nav current class and catalog partial existence. Still FAIL on public type `index.php` files.

- [ ] **Step 5: Commit**

```bash
git add wp-content/uploads/piano-type-pages.css partials/pianos-category-nav.php partials/piano-type-catalog.php
git commit -m "Add shared piano type catalog template"
```

---

### Task 4: Rebuild Disklavier

**Files:**
- Modify: `disklavier-pianos/index.php` (replace public page; leave `index.legacy.php` untouched)

**Interfaces:**
- Consumes: `$catalog` keys from Task file-structure; `partials/piano-type-catalog.php`
- Produces: public `/disklavier-pianos/` catalog

- [ ] **Step 1: Replace the public Disklavier page**

Write `disklavier-pianos/index.php`:

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Yamaha Disklavier Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Yamaha Disklavier pianos offer the best of acoustic sound and digital control. Visit Piano Depot in Olyphant, PA to find the perfect model for your needs.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/disklavier-pianos/',
    'eyebrow' => 'Yamaha Disklavier',
    'title' => 'Disklavier Pianos',
    'intro' => 'In 1982, it started with a simple idea: an acoustic piano with a record and playback system unlike any other. More than 30 years of innovation created a piano that can faithfully reproduce every nuance of a performance and stream it wirelessly — including into your living room.',
    'hero_image' => '/wp-content/uploads/2021/06/vA-6hReg.jpeg',
    'yamaha_href' => 'https://usa.yamaha.com/products/musical_instruments/pianos/disklavier/index.html',
    'yamaha_label' => 'View catalog on Yamaha’s website',
    'models' => [
        ['name' => 'Enspire CL', 'href' => '/product/enspire-cl/', 'image' => '/wp-content/uploads/2021/11/CL-lineup-300x300.jpg', 'description' => 'Disklavier Enspire CL player piano.', 'label' => 'Disklavier'],
        ['name' => 'Enspire ST', 'href' => '/product/enspire-st/', 'image' => '/wp-content/uploads/2021/11/ST-lineup-300x300.jpg', 'description' => 'Disklavier Enspire ST player piano.', 'label' => 'Disklavier'],
        ['name' => 'Enspire Pro', 'href' => '/product/enspire-pro/', 'image' => '/wp-content/uploads/2021/11/012-Disklavier-Enspire_Recording-Studio_6R9A5990-300x300.jpg', 'description' => 'Disklavier Enspire Pro for recording and performance.', 'label' => 'Disklavier'],
        ['name' => 'DKC-900 Upgrade Kit', 'href' => '/product/dkc-900-upgrade-kit/', 'image' => '/wp-content/uploads/2021/11/DKC-900.jpg', 'description' => 'Upgrade kit for compatible Disklavier pianos.', 'label' => 'Disklavier'],
    ],
    'videos' => [
        ['id' => 'T9Qv5B1Eb-k', 'caption' => 'Overview of the Yamaha Disklavier Enspire Player Piano App'],
        ['id' => 'U1cNpWSI9Nw', 'caption' => 'Yamaha Enspire Piano Is A Song Writers Dream Piano'],
        ['id' => 'Xu36GOKXs5M', 'caption' => 'Expand Your Acoustic Piano With MIDI'],
        ['id' => 'kvUFUKFUDC4', 'caption' => 'Enspire Pianos Worlds Best Piano For Recording Music'],
        ['id' => 'hlvBms8IW7o', 'caption' => 'Smart Key Helps Anyone To Play the Piano'],
    ],
    'cta_title' => 'Make an appointment to see a Disklavier',
    'cta_text' => 'These instruments are shown in store. Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
```

- [ ] **Step 2: Run tests**

Run: `php tests/run.php`

Expected: Disklavier assertions PASS (no slider, five unique video ids, catalog partial). Other type slugs still FAIL.

Open [http://localhost:8006/disklavier-pianos/](http://localhost:8006/disklavier-pianos/). Confirm photo hero, four cards, five videos at readable size, no Quick View / search / black nav, appointment CTA, current Disklavier chip.

- [ ] **Step 3: Commit**

```bash
git add disklavier-pianos/index.php
git commit -m "Rebuild Disklavier as a piano type catalog"
```

---

### Task 5: Rebuild the other Yamaha type pages

**Files:**
- Modify: `acoustic-grand-pianos/index.php`
- Modify: `acoustic-silent-trans-acoustic-pianos/index.php`
- Modify: `acoustic-upright-pianos/index.php`
- Modify: `portable-digital-pianos/index.php`
- Modify: `workstation-keyboards/index.php`

**Interfaces:**
- Consumes: same `$catalog` shape and `partials/piano-type-catalog.php`
- Produces: five more public catalogs. No Yamaha button on these pages (existing “CLICK HERE FOR DETAILS” URLs point at the wrong category).

- [ ] **Step 1: Acoustic Grand**

Write `acoustic-grand-pianos/index.php`:

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Yamaha Acoustic Grand Pianos | Piano Depot in Olyphant, PA',
    'description' => 'Explore Yamaha acoustic grand pianos at Piano Depot in Olyphant, PA. Shop the best grand pianos with expert advice & financing available. Visit us today!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/acoustic-grand-pianos/',
    'eyebrow' => 'Yamaha Acoustic Grands',
    'title' => 'Acoustic Grand Pianos',
    'intro' => 'Each Yamaha acoustic grand is assembled by artisans in a tradition of old-world craftsmanship. These instruments are sold in store only — call or email to make an appointment.',
    'hero_image' => '/wp-content/uploads/2021/06/j2glbwww.jpeg',
    'models' => [
        ['name' => 'GB1K / GC Series: 5′ to 5′ 8″', 'href' => '/product/gb1k-gc-series-5-to-5-8/', 'image' => '/wp-content/uploads/2021/09/GB1K-1-PolishedEbony-piano-300x300.jpg', 'description' => 'Yamaha baby grand series.', 'label' => 'Grand'],
        ['name' => 'CX Series: 5′ 3″ to 7′ 6″', 'href' => '/product/cx-series-5-3-to-7-6/', 'image' => '/wp-content/uploads/2021/09/CX_Satin-Ebony-300x300.jpg', 'description' => 'Yamaha CX conservatory grands.', 'label' => 'Grand'],
        ['name' => 'SX Series: 6′ 1″ to 7′ 6″', 'href' => '/product/sx-series-6-1-to-7-6/', 'image' => '/wp-content/uploads/2021/09/SX-polishedebony-300x300.jpg', 'description' => 'Yamaha SX performance grands.', 'label' => 'Grand'],
        ['name' => 'CF Series: 6′ 3″ to 9′', 'href' => '/product/cf-series-6-3-to-9/', 'image' => '/wp-content/uploads/2021/09/CF-polishedebony-300x300.jpg', 'description' => 'Yamaha CF concert grands.', 'label' => 'Grand'],
    ],
    'videos' => [
        ['id' => 'tTg2D3rFdcw', 'caption' => 'Yamaha Grand Piano from Kakegawa Factory'],
    ],
    'cta_title' => 'Make an appointment to see a grand',
    'cta_text' => 'These fine instruments are sold in store only. Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
```

Do not include the expired 0% APR October 2024–January 2025 promo.

- [ ] **Step 2: Silent & TransAcoustic**

Write `acoustic-silent-trans-acoustic-pianos/index.php`:

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Silent & Trans Acoustic Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Discover Yamaha Silent & TransAcoustic pianos at Piano Depot in Olyphant, PA. Check out cutting-edge technology for quiet or enhanced acoustic playing.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/acoustic-silent-trans-acoustic-pianos/',
    'eyebrow' => 'Silent & TransAcoustic',
    'title' => 'Silent & TransAcoustic Pianos',
    'intro' => 'By day it is a world-renowned acoustic piano. By night, the neighbors will not hear a thing. SILENT Piano puts concert-grand sound in your headphones, and TransAcoustic uses the soundboard to amplify digital voices through the instrument itself.',
    'hero_image' => '/wp-content/uploads/2021/06/duoXPjwM.jpeg',
    'models' => [
        ['name' => 'TA2 TransAcoustic', 'href' => '/product/ta2-transacoustic/', 'image' => '/wp-content/uploads/2021/09/TA2-lineup-300x300.jpg', 'description' => 'TransAcoustic piano with digital voices through the soundboard.', 'label' => 'TransAcoustic'],
        ['name' => 'SC2 Silent Piano', 'href' => '/product/sc2-silent-piano/', 'image' => '/wp-content/uploads/2021/09/b2-SC2-pe-300x300.jpg', 'description' => 'Silent system on a Yamaha acoustic upright.', 'label' => 'Silent'],
        ['name' => 'SH2 Silent Piano', 'href' => '/product/sh2-silent-piano/', 'image' => '/wp-content/uploads/2021/09/gc1PEC-SH2_Front-300x300.jpg', 'description' => 'Silent system on a Yamaha acoustic grand.', 'label' => 'Silent'],
    ],
    'videos' => [
        ['id' => 'Xu36GOKXs5M', 'caption' => 'Expand your acoustic piano with MIDI'],
    ],
    'cta_title' => 'Hear a Silent or TransAcoustic piano',
    'cta_text' => 'Call or text ahead for an in-store demonstration. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
```

- [ ] **Step 3: Acoustic Upright**

Write `acoustic-upright-pianos/index.php` with this `$page` block, the `$catalog` below, and the same header/partial/footer requires as Disklavier:

```php
$page = [
    'title' => 'Acoustic Upright Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Find Yamaha upright pianos at Piano Depot in Olyphant, PA. From beginner to professional models, we have the perfect upright piano for you! Visit our showroom!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
```

```php
$catalog = [
    'nav' => '/acoustic-upright-pianos/',
    'eyebrow' => 'Yamaha Acoustic Uprights',
    'title' => 'Acoustic Upright Pianos',
    'intro' => 'Elegant, compact pianos with a responsive keyboard and a clear, resonant tone — for beginners, budding virtuosos, and accomplished musicians.',
    'hero_image' => '/wp-content/uploads/2021/06/IgVWKrB0.jpeg',
    'models' => [
        ['name' => 'b Series', 'href' => '/product/b-series/', 'image' => '/wp-content/uploads/2021/11/bSeries_gallery-300x300.jpg', 'description' => 'Yamaha b Series uprights.', 'label' => 'Upright'],
        ['name' => 'P22 Piano', 'href' => '/product/p22-piano/', 'image' => '/wp-content/uploads/2021/11/pseries_gallery-300x300.webp', 'description' => 'Yamaha P22 studio upright.', 'label' => 'Upright'],
        ['name' => 'U Series', 'href' => '/product/u-series/', 'image' => '/wp-content/uploads/2021/11/USeries_Gallery-300x300.jpg', 'description' => 'Yamaha U Series uprights.', 'label' => 'Upright'],
        ['name' => 'YUS Series', 'href' => '/product/yus-series/', 'image' => '/wp-content/uploads/2021/11/YUS5-gallery-300x300.jpg', 'description' => 'Yamaha YUS Series uprights.', 'label' => 'Upright'],
    ],
    'videos' => [
        ['id' => 'fKGu54YKkSE', 'caption' => 'The Yamaha Story'],
    ],
    'cta_title' => 'Make an appointment to see an upright',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
```

- [ ] **Step 4: Portable Digital**

Write `portable-digital-pianos/index.php` with this `$page` and `$catalog`, plus the same header/partial/footer requires as Disklavier:

```php
$page = [
    'title' => 'Yamaha Portable Digital Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Need a lightweight, space-saving piano? Piano Depot in Olyphant, PA offers the best selection of portable digital pianos perfect for home & stage. Visit us now!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
```

`$catalog`:

```php
$catalog = [
    'nav' => '/portable-digital-pianos/',
    'eyebrow' => 'Yamaha Portable Digital',
    'title' => 'Portable Digital Pianos',
    'intro' => 'Enjoy the touch and tone of a Yamaha acoustic piano wherever the music takes you. These portable, full-feature digital pianos let you turn any space into a studio, club stage, or concert hall.',
    'hero_image' => '/wp-content/uploads/2021/06/CpNzSo3U.jpeg',
    'models' => [
        ['name' => 'P-515', 'href' => '/product/p-515/', 'image' => '/wp-content/uploads/2021/07/P-515B-with-standpedal-300x300.jpg', 'description' => 'Flagship portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-125', 'href' => '/product/p-125/', 'image' => '/wp-content/uploads/2021/07/P-125B-with-standpedal-300x300.jpg', 'description' => 'Compact portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-121', 'href' => '/product/p-121/', 'image' => '/wp-content/uploads/2021/07/P-121BK-with-standpedal-300x300.jpg', 'description' => 'Shorter-key portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-45', 'href' => '/product/p-45/', 'image' => '/wp-content/uploads/2021/07/P-45-with-Stand-300x300.jpg', 'description' => 'Entry portable digital piano.', 'label' => 'Portable'],
        ['name' => 'DGX-670 Portable Grand Piano', 'href' => '/product/dgx-670-portable-grand-piano/', 'image' => '/wp-content/uploads/2021/07/DGX-670-Black-new-300x300.jpg', 'description' => 'Portable grand with accompaniment.', 'label' => 'Portable'],
    ],
    'videos' => [
        ['id' => 'WqSXOM49GZA', 'caption' => 'Yamaha P-515 Digital Piano Overview'],
    ],
    'cta_title' => 'Try a portable Yamaha in the showroom',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
```

Do not keep the current button to the Clavinova CVP-701 Yamaha URL.

- [ ] **Step 5: Workstations**

Write `workstation-keyboards/index.php` with this `$page` and `$catalog`, plus the same header/partial/footer requires as Disklavier:

```php
$page = [
    'title' => 'Workstation Keyboards in Olyphant, PA | Piano Depot',
    'description' => 'Looking for a powerful workstation keyboard? Piano Depot in Olyphant, PA offers top-rated professional keyboards with advanced features for musicians.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
```

`$catalog`:

```php
$catalog = [
    'nav' => '/workstation-keyboards/',
    'eyebrow' => 'Yamaha Arrangers',
    'title' => 'Workstation Keyboards',
    'intro' => 'Songwriting and performance keyboards with pro-level connectivity, real instrument Voices, Styles, effects, and a redesigned interface.',
    'hero_image' => '/wp-content/uploads/2021/06/wsm9Gy8I.jpeg',
    'models' => [
        ['name' => 'PSR-SX600', 'href' => '/product/psr-sx600/', 'image' => '/wp-content/uploads/2021/07/SX600_frontview-300x300.webp', 'description' => 'Yamaha PSR-SX600 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'PSR-SX700', 'href' => '/product/psr-sx700/', 'image' => '/wp-content/uploads/2021/07/SX700_front.png', 'description' => 'Yamaha PSR-SX700 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'PSR-SX900', 'href' => '/product/psr-sx900/', 'image' => '/wp-content/uploads/2021/07/SX900_front.jpg', 'description' => 'Yamaha PSR-SX900 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'Genos', 'href' => '/product/genos/', 'image' => '/wp-content/uploads/2021/09/Genos_Uptop-2-300x300.jpg', 'description' => 'Yamaha Genos flagship arranger.', 'label' => 'Workstation'],
    ],
    'videos' => [
        ['id' => '32AdjKpF8b4', 'caption' => 'Playing the Yamaha PSR-S970'],
    ],
    'cta_title' => 'See a workstation in the showroom',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
```

Use the same header/partial/footer includes as Disklavier.

- [ ] **Step 6: Run tests**

Run: `php tests/run.php`

Expected: PASS for the five Yamaha type slugs and Disklavier. FAIL only for `used-and-refurbished` if that page is still WordPress markup.

- [ ] **Step 7: Commit**

```bash
git add acoustic-grand-pianos/index.php acoustic-silent-trans-acoustic-pianos/index.php acoustic-upright-pianos/index.php portable-digital-pianos/index.php workstation-keyboards/index.php
git commit -m "Rebuild remaining Yamaha piano type catalogs"
```

---

### Task 6: Rebuild Used & Refurbished

**Files:**
- Modify: `used-and-refurbished/index.php`

**Interfaces:**
- Consumes: `$catalog['groups']`, `$catalog['gallery']`, `$catalog['videos']`
- Produces: grouped used catalog; subcategory URLs unchanged

- [ ] **Step 1: Replace the public used page**

Write `used-and-refurbished/index.php`. Use this `$page` block (the current SEO title and description) and the `$catalog` below, then the same header/partial/footer requires as Disklavier.

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Shop Used & Refurbished Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Looking for a quality used piano? Piano Depot in Olyphant, PA offers expertly refurbished grand, upright, and digital pianos at great prices. Visit us today!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/used-and-refurbished/',
    'eyebrow' => 'Used & Refurbished',
    'title' => 'Used & Refurbished Pianos',
    'intro' => 'Our inventory is constantly changing and it is best to call for what is available. We keep an assortment of used and refurbished grands and uprights — spinets, consoles, and studio consoles — expertly refurbished, inspected, and finely tuned.',
    'hero_image' => '/wp-content/uploads/2021/06/XVVoBzWk.jpeg',
    'groups' => [
        [
            'id' => 'used-grands',
            'title' => 'Restored Grand Pianos',
            'href' => '/used-refurbished-grand-pianos/',
            'models' => [
                ['name' => 'Story & Clark (Player)', 'href' => '/product/story-clark/', 'image' => '/wp-content/uploads/2022/09/SrotyClark-Player-front-300x300.jpg', 'description' => 'Refurbished player grand.', 'label' => 'Used Grand'],
                ['name' => 'Baldwin Grand', 'href' => '/product/baldwin-grand/', 'image' => '/wp-content/uploads/2021/11/Baldwin-Grand-300x300.jpg', 'description' => 'Refurbished Baldwin grand.', 'label' => 'Used Grand'],
                ['name' => 'Yamaha GH1', 'href' => '/product/yamaha-gh1/', 'image' => '/wp-content/uploads/2021/11/IMG_0815-300x300.jpg', 'description' => 'Refurbished Yamaha GH1 grand.', 'label' => 'Used Grand'],
            ],
        ],
        [
            'id' => 'used-uprights',
            'title' => 'Restored Upright Pianos',
            'href' => '/used-refurbished-upright-pianos/',
            'models' => [
                ['name' => 'Knabe & Co', 'href' => '/product/knabe-co-player-piano/', 'image' => '/wp-content/uploads/2022/03/knabeco01.large_-300x300.jpg', 'description' => 'Refurbished Knabe upright.', 'label' => 'Used Upright'],
                ['name' => 'Yamaha U3', 'href' => '/product/yamaha-u3/', 'image' => '/wp-content/uploads/2022/03/yamaha-u3-01.large_-300x300.jpg', 'description' => 'Refurbished Yamaha U3.', 'label' => 'Used Upright'],
                ['name' => 'Young Chang F-110', 'href' => '/product/young-chang-f110/', 'image' => '/wp-content/uploads/2022/09/YoungChang-F110-farside-300x300.jpg', 'description' => 'Refurbished Young Chang F-110.', 'label' => 'Used Upright'],
                ['name' => 'Baldwin 243 SB', 'href' => '/product/baldwin-243-sb/', 'image' => '/wp-content/uploads/2021/11/Baldwin-243B.large_-300x300.jpg', 'description' => 'Refurbished Baldwin 243 SB.', 'label' => 'Used Upright'],
                ['name' => 'Kawai', 'href' => '/product/kawai-walnut/', 'image' => '/wp-content/uploads/2022/09/KawaiW-front-300x300.jpg', 'description' => 'Refurbished Kawai walnut upright.', 'label' => 'Used Upright'],
                ['name' => 'Shaw', 'href' => '/product/shaw/', 'image' => '/wp-content/uploads/2021/11/Shaw-Upright.jpeg', 'description' => 'Refurbished Shaw upright.', 'label' => 'Used Upright'],
                ['name' => 'Yamaha M214B W', 'href' => '/product/yamaha-m214b/', 'image' => '/wp-content/uploads/2021/11/Yamaha-M214B-Walnut.large_-1-300x300.jpg', 'description' => 'Refurbished Yamaha M214B.', 'label' => 'Used Upright'],
            ],
        ],
        [
            'id' => 'used-consoles',
            'title' => 'Restored Console Pianos',
            'href' => '/used-refurbished-console-pianos/',
            'models' => [
                ['name' => 'Kimball', 'href' => '/product/kimball-console/', 'image' => '/wp-content/uploads/2022/09/Kimball-BrMah-front-300x300.jpg', 'description' => 'Refurbished Kimball console.', 'label' => 'Used Console'],
                ['name' => 'Hyundai U810', 'href' => '/product/hyundai-u810/', 'image' => '/wp-content/uploads/2021/11/Hyundai.large_-300x300.jpg', 'description' => 'Refurbished Hyundai U810.', 'label' => 'Used Console'],
                ['name' => 'Kimball Artist Console', 'href' => '/product/kimball-artist-console/', 'image' => '/wp-content/uploads/2021/11/Kimball-Artist-Console-4244-GoldenOak.large_-300x300.jpg', 'description' => 'Refurbished Kimball artist console.', 'label' => 'Used Console'],
            ],
        ],
        [
            'id' => 'used-spinets',
            'title' => 'Restored Spinet Pianos',
            'href' => '/used-refurbished-spinet-pianos/',
            'models' => [
                ['name' => 'Baldwin Howard', 'href' => '/product/baldwin-howard/', 'image' => '/wp-content/uploads/2021/11/Baldwin-Howard-GoldenOak.large_-300x300.jpg', 'description' => 'Refurbished Baldwin Howard spinet.', 'label' => 'Used Spinet'],
                ['name' => 'Winter DarkOak Cabinet', 'href' => '/product/winter-piano/', 'image' => '/wp-content/uploads/2021/11/Winter-Spinet-BrMah.large_-300x300.jpg', 'description' => 'Refurbished Winter spinet.', 'label' => 'Used Spinet'],
            ],
        ],
        [
            'id' => 'used-digital',
            'title' => 'Restored Digital Pianos',
            'href' => '/used-refurbished-digital-pianos/',
            'models' => [],
        ],
    ],
    'gallery' => [
        ['src' => '/wp-content/uploads/2021/11/IMG_0372.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0371.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0365.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0375.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0318.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0317.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0302-1.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0301.jpg', 'alt' => 'Used pianos at Piano Depot'],
    ],
    'videos' => [
        ['id' => 'Cz3oCAp2Nts', 'caption' => 'Hallet Davis QRS Player Demo'],
    ],
    'cta_title' => 'Call for current used inventory',
    'cta_text' => 'Inventory changes. Please call or text ahead to see what is in the showroom and warehouse. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
```

The digital group has no product cards on the landing page today. The chip still links to `/used-refurbished-digital-pianos/`. The partial skips an empty model grid and keeps the “Browse this category” link.

- [ ] **Step 2: Run tests**

Run: `php tests/run.php`

Expected: All seven type-page file assertions PASS. `All tests passed`.

- [ ] **Step 3: Commit**

```bash
git add used-and-refurbished/index.php partials/piano-type-catalog.php
git commit -m "Rebuild used and refurbished as a grouped catalog"
```

---

### Task 7: Visual pass and leftover-markup check

**Files:**
- Test: `tests/test_piano_type_pages.php`
- Inspect in the browser: the seven type URLs

**Interfaces:**
- Consumes: completed public pages and `http://localhost:8006/`
- Produces: confirmation that the rebuilt pages match the spec visually

- [ ] **Step 1: Run the full suite and whitespace check**

```bash
php tests/run.php
git diff --check
```

Expected: `All tests passed` and no whitespace errors.

- [ ] **Step 2: Browser check**

With `php -S localhost:8006 router.php` running, open each URL at desktop width (~1440) and a narrow viewport (~390):

- [http://localhost:8006/disklavier-pianos/](http://localhost:8006/disklavier-pianos/)
- [http://localhost:8006/acoustic-grand-pianos/](http://localhost:8006/acoustic-grand-pianos/)
- [http://localhost:8006/acoustic-silent-trans-acoustic-pianos/](http://localhost:8006/acoustic-silent-trans-acoustic-pianos/)
- [http://localhost:8006/acoustic-upright-pianos/](http://localhost:8006/acoustic-upright-pianos/)
- [http://localhost:8006/portable-digital-pianos/](http://localhost:8006/portable-digital-pianos/)
- [http://localhost:8006/workstation-keyboards/](http://localhost:8006/workstation-keyboards/)
- [http://localhost:8006/used-and-refurbished/](http://localhost:8006/used-and-refurbished/)

Confirm on every page: cream collection nav with the current chip filled red; photo hero; model cards; appointment CTA; no slider, Quick View, product search, or black duplicate nav. Disklavier videos are large 16:9, five unique ids. Used page shows group chips and warehouse photos. Clavinova and Closeout still look as they do today.

- [ ] **Step 3: Commit only if Step 2 required CSS or partial fixes**

```bash
git add wp-content/uploads/piano-type-pages.css partials/piano-type-catalog.php
git commit -m "Tighten piano type catalog layout after visual pass"
```

If there were no visual fixes, do not create an empty commit.

Do not push `origin main` unless the user asks.

---

## Self-review (spec coverage)

| Spec requirement | Task |
| --- | --- |
| Seven leftover type URLs in, Clavinova/Closeout/products out | Tasks 4–6; Global Constraints |
| Shared partial + `$catalog` + new CSS | Task 3 |
| `index.legacy.php` rollback copies | Task 2 |
| Photo hero, cards, videos, CTA, current nav chip | Tasks 3–4 |
| Keep existing models, images, copy; no invented MAP | Tasks 4–6 arrays |
| Yamaha CTA only when the URL matches the type | Task 4 Disklavier only |
| Drop slider, Quick View, search, black nav, GF sidebar, expired APR | Tasks 1, 4–6 |
| Used groups + warehouse gallery | Task 6 |
| Tests for 200/forbidden markup/legacy/video ids | Tasks 1, 7 |
| Visual desktop + narrow | Task 7 |
| Do not push main | Global Constraints, Task 7 |
