# Piano Depot Static PHP Clone Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ship a visual clone of pianodepot.com as plain PHP files with a shared header/footer, no database, homepage at `/`.

**Architecture:** Project root is the web root. Each WordPress permalink is a directory with `index.php`. Shared chrome lives in `partials/`. A scrape + convert tool copies live HTML/CSS/images, splits chrome from body, and rewrites internal links to root-relative paths.

**Tech Stack:** PHP 8.4 (no Composer, no Laravel, no PHPUnit). PHP built-in server + `router.php` locally. Apache `.htaccess` for production 404s. Live source: WordPress + OceanWP + Elementor + WooCommerce + Gravity Forms.

## Global Constraints

- No database. No Laravel. No Composer. No PHPUnit.
- Homepage is `/` via `/index.php`. No `/public` subdirectory.
- Includes use `$_SERVER['DOCUMENT_ROOT'] . '/partials/...'` so nested `/product/{slug}/` works.
- Keep original `/wp-content/...` asset paths. Do not load CSS/images from the live WordPress host.
- Do not clone `/cart/`, `/checkout/`, `/account/`, `/shop/`, `/home/`, `/home-11-1-23-holiday-promotion/`, or slugs ending in `-2`.
- Drop ACCOUNT and cart from the cloned nav. Product “Add to cart” becomes call/text `570-352-5501` plus a Contact link.
- Phone: `570-352-5501`. Tel: `+15703525501`. Address: `225 W. Lackawanna Ave., Olyphant, PA 18447`.
- Local serve: `php -S localhost:8003 router.php` from the project root. Open `http://localhost:8003/`.
- `display_errors` off in `partials/config.php`.
- External embeds (Vimeo, YouTube, Google Maps, Facebook, Google Fonts) stay remote. Footer links to pianoorgandepot.com stay external.
- Tests are `php tests/run.php` using `assert`-style helpers. No PHPUnit.

---

## File structure

| Path | Responsibility |
| --- | --- |
| `partials/config.php` | Business facts, `PD_ROOT`, `display_errors` |
| `partials/resolve.php` | Map a request URI to a file, a PHP page, or 404 |
| `partials/header.php` | `<head>` + skip link + `#top-bar-wrap` + `#site-header` |
| `partials/footer.php` | `#footer` through `</html>` |
| `index.php` | Homepage body |
| `404.php` | Not-found page using the same chrome |
| `router.php` | PHP built-in server front controller |
| `.htaccess` | Apache `ErrorDocument 404 /404.php` |
| `forms/send.php` | POST handler for Contact, moving, credit |
| `{slug}/index.php` | One cloned content page per URL |
| `product/{slug}/index.php` | Static catalog product pages |
| `wp-content/` | Copied CSS, JS, fonts, images (original paths) |
| `tools/lib.php` | URL filter, path map, HTML split/rewrite (testable) |
| `tools/scrape.php` | Download sitemap URLs + local assets |
| `tools/convert.php` | Turn scraped HTML into page files + partials |
| `tests/run.php` | Test runner |
| `tests/helpers.php` | `expect()` helper |
| `tests/test_*.php` | One file per area |
| `PAGES.md` | Cloned URL checklist |
| `.gitignore` | `scrape/raw/`, `.DS_Store` |
| `scrape/raw/` | Raw downloaded HTML (not committed) |

---

### Task 1: Test runner and config

**Files:**
- Create: `tests/helpers.php`
- Create: `tests/run.php`
- Create: `tests/test_config.php`
- Create: `partials/config.php`
- Create: `.gitignore`

**Interfaces:**
- Consumes: nothing
- Produces:
  - `pd_config(): array` with keys `site_name`, `phone`, `phone_tel`, `address`, `email_to`, `display_errors`
  - `PD_ROOT` constant = project root
  - `tests/run.php` exits `0` on pass, `1` on fail

- [ ] **Step 1: Write the failing test**

Create `tests/helpers.php`:

```php
<?php

function expect(bool $condition, string $message): void
{
    global $pd_failures;
    if (!isset($pd_failures)) {
        $pd_failures = 0;
    }
    if ($condition) {
        echo "PASS: {$message}\n";
        return;
    }
    $pd_failures++;
    fwrite(STDERR, "FAIL: {$message}\n");
}
```

Create `tests/run.php`:

```php
<?php

$pd_failures = 0;
require __DIR__ . '/helpers.php';

foreach (glob(__DIR__ . '/test_*.php') as $file) {
    echo "== " . basename($file) . " ==\n";
    require $file;
}

if ($pd_failures > 0) {
    fwrite(STDERR, "\n{$pd_failures} failure(s)\n");
    exit(1);
}

echo "\nAll tests passed\n";
exit(0);
```

Create `tests/test_config.php`:

```php
<?php

require dirname(__DIR__) . '/partials/config.php';

$cfg = pd_config();

expect(defined('PD_ROOT'), 'PD_ROOT is defined');
expect(PD_ROOT === dirname(__DIR__), 'PD_ROOT is the project root');
expect($cfg['site_name'] === 'Piano Depot', 'site_name');
expect($cfg['phone'] === '570-352-5501', 'phone');
expect($cfg['phone_tel'] === '+15703525501', 'phone_tel');
expect($cfg['address'] === '225 W. Lackawanna Ave., Olyphant, PA 18447', 'address');
expect(is_string($cfg['email_to']) && $cfg['email_to'] !== '', 'email_to is set');
expect($cfg['display_errors'] === false, 'display_errors is false');
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL because `partials/config.php` does not exist (`Failed opening required .../partials/config.php`).

- [ ] **Step 3: Write minimal implementation**

Create `.gitignore`:

```
.DS_Store
scrape/raw/
```

Create `partials/config.php`:

```php
<?php

if (!defined('PD_ROOT')) {
    define('PD_ROOT', dirname(__DIR__));
}

ini_set('display_errors', '0');
error_reporting(E_ALL);

function pd_config(): array
{
    return [
        'site_name' => 'Piano Depot',
        'phone' => '570-352-5501',
        'phone_tel' => '+15703525501',
        'address' => '225 W. Lackawanna Ave., Olyphant, PA 18447',
        'email_to' => 'info@pianodepot.com',
        'display_errors' => false,
    ];
}

$pd = pd_config();
ini_set('display_errors', $pd['display_errors'] ? '1' : '0');
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/run.php`

Expected: `All tests passed` and exit 0.

- [ ] **Step 5: Commit**

```bash
git add tests/helpers.php tests/run.php tests/test_config.php partials/config.php .gitignore
git commit -m "Add config, test runner, and project gitignore."
```

---

### Task 2: Path resolver, router, and 404

**Files:**
- Create: `partials/resolve.php`
- Create: `router.php`
- Create: `404.php`
- Create: `.htaccess`
- Create: `tests/test_resolve.php`

**Interfaces:**
- Consumes: `PD_ROOT` from `partials/config.php`
- Produces: `pd_resolve(string $uri, string $root): array` with shape `['kind' => 'file'|'php'|'not_found', 'path' => string]`
  - `'file'` — static asset; router must `return false`
  - `'php'` — require `$path`
  - `'not_found'` — HTTP 404 and require `404.php`

- [ ] **Step 1: Write the failing test**

Create `tests/test_resolve.php`:

```php
<?php

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/partials/resolve.php';

$root = PD_ROOT;

$home = pd_resolve('/', $root);
expect($home['kind'] === 'php', 'home is php');
expect($home['path'] === $root . '/index.php', 'home path is index.php');

$home2 = pd_resolve('', $root);
expect($home2['kind'] === 'php' && $home2['path'] === $root . '/index.php', 'empty uri is home');

$nested = pd_resolve('/product/p-515/', $root);
expect($nested['kind'] === 'php', 'nested directory with index.php is php');
expect($nested['path'] === $root . '/product/p-515/index.php', 'nested path');

$nestedNoSlash = pd_resolve('/product/p-515', $root);
expect($nestedNoSlash['kind'] === 'php' && $nestedNoSlash['path'] === $root . '/product/p-515/index.php', 'nested without trailing slash');

$missing = pd_resolve('/this-page-does-not-exist/', $root);
expect($missing['kind'] === 'not_found', 'unknown url is not_found');

$css = pd_resolve('/wp-content/themes/oceanwp/assets/css/style.min.css', $root);
expect($css['kind'] === 'file' || $css['kind'] === 'not_found', 'css resolves as file when present else not_found until assets exist');

$blocked = pd_resolve('/tests/run.php', $root);
expect($blocked['kind'] === 'not_found', 'tests/ is not web-served');

$blocked2 = pd_resolve('/partials/config.php', $root);
expect($blocked2['kind'] === 'not_found', 'partials/ is not web-served');

$blocked3 = pd_resolve('/tools/scrape.php', $root);
expect($blocked3['kind'] === 'not_found', 'tools/ is not web-served');
```

Before this test can pass for nested php, create placeholder files the resolver can find:

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL because `partials/resolve.php` does not exist.

- [ ] **Step 3: Write minimal implementation**

Create `partials/resolve.php`:

```php
<?php

function pd_resolve(string $uri, string $root): array
{
    $path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?? '/');
    if ($path === '' || $path === false) {
        $path = '/';
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    $blocked = ['/tests', '/partials', '/tools', '/docs', '/scrape', '/.git'];
    foreach ($blocked as $prefix) {
        if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
            return ['kind' => 'not_found', 'path' => $root . '/404.php'];
        }
    }

    if ($path === '/') {
        return ['kind' => 'php', 'path' => $root . '/index.php'];
    }

    $full = $root . $path;
    if (is_file($full)) {
        return ['kind' => 'file', 'path' => $full];
    }

    $dir = rtrim($full, '/');
    if (is_dir($dir) && is_file($dir . '/index.php')) {
        return ['kind' => 'php', 'path' => $dir . '/index.php'];
    }

    return ['kind' => 'not_found', 'path' => $root . '/404.php'];
}
```

Create `router.php`:

```php
<?php

require __DIR__ . '/partials/config.php';
require __DIR__ . '/partials/resolve.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$resolved = pd_resolve($uri, PD_ROOT);

if ($resolved['kind'] === 'file') {
    return false;
}

if ($resolved['kind'] === 'php') {
    require $resolved['path'];
    return true;
}

http_response_code(404);
require PD_ROOT . '/404.php';
return true;
```

Create `404.php` (stub chrome until Task 3; must be valid PHP and mention Home / Pianos we sell / Contact):

```php
<?php
http_response_code(404);
$cfg = function_exists('pd_config') ? pd_config() : ['site_name' => 'Piano Depot'];
$page = [
    'title' => 'Page not found | Piano Depot',
    'description' => 'That page is not on Piano Depot.',
    'extra_css' => [],
];
if (is_file(PD_ROOT . '/partials/header.php')) {
    require PD_ROOT . '/partials/header.php';
} else {
    echo '<!DOCTYPE html><html><head><title>' . htmlspecialchars($page['title']) . '</title></head><body>';
}
?>
<main id="main" class="site-main clr" role="main">
    <div id="content-wrap" class="container clr">
        <h1>Page not found</h1>
        <p>That page is gone. Try one of these:</p>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/pianos-we-sell/">Pianos we sell</a></li>
            <li><a href="/contact-us/">Contact</a></li>
        </ul>
    </div>
</main>
<?php
if (is_file(PD_ROOT . '/partials/footer.php')) {
    require PD_ROOT . '/partials/footer.php';
} else {
    echo '</body></html>';
}
```

Create `.htaccess`:

```
ErrorDocument 404 /404.php
Options -Indexes
```

Create placeholder nested page so the nested resolve test passes:

```php
<?php
// product/p-515/index.php — replaced with the real clone in Task 7
$page = ['title' => 'P-515', 'description' => '', 'extra_css' => []];
echo 'placeholder';
```

Directory: `product/p-515/index.php`

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/run.php`

Expected: `All tests passed`. If `pd_resolve` directory logic is messy, simplify until the four cases pass: `/`, `/product/p-515/`, `/product/p-515`, `/this-page-does-not-exist/`, and the three blocked prefixes.

- [ ] **Step 5: Commit**

```bash
git add partials/resolve.php router.php 404.php .htaccess product/p-515/index.php tests/test_resolve.php
git commit -m "Add URI resolver, built-in-server router, and 404 page."
```

---

### Task 3: Stub layout so `/` renders

**Files:**
- Create: `partials/header.php`
- Create: `partials/footer.php`
- Create: `index.php`
- Create: `tests/test_layout.php`

**Interfaces:**
- Consumes: `$page` array with `title` (string), `description` (string), `extra_css` (list of href strings). `pd_config()`.
- Produces: Every page that sets `$page` then `require`s header + footer renders a full HTML document. Header includes skip link, phone, address, logo link to `/`. Footer includes copyright name.

- [ ] **Step 1: Write the failing test**

Create `tests/test_layout.php`:

```php
<?php

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
require_once dirname(__DIR__) . '/partials/config.php';

ob_start();
$page = [
    'title' => 'Layout Test | Piano Depot',
    'description' => 'Test description',
    'extra_css' => ['/wp-content/uploads/elementor/css/post-3068.css'],
];
require dirname(__DIR__) . '/partials/header.php';
echo '<main id="main">body</main>';
require dirname(__DIR__) . '/partials/footer.php';
$html = ob_get_clean();

expect(str_contains($html, '<title>Layout Test | Piano Depot</title>'), 'title from $page');
expect(str_contains($html, 'name="description"'), 'meta description tag exists');
expect(str_contains($html, 'Test description'), 'description from $page');
expect(str_contains($html, '/wp-content/uploads/elementor/css/post-3068.css'), 'extra_css printed');
expect(str_contains($html, 'Skip to content'), 'skip link');
expect(str_contains($html, '570-352-5501'), 'phone in chrome');
expect(str_contains($html, '225 W. Lackawanna Ave.'), 'address in chrome');
expect(str_contains($html, 'href="/"'), 'home link');
expect(str_contains($html, 'id="site-header"') || str_contains($html, 'site-header'), 'header landmark');
expect(str_contains($html, 'Piano Depot'), 'site name in chrome');
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL because `partials/header.php` does not exist.

- [ ] **Step 3: Write minimal implementation**

These stubs are replaced by live OceanWP markup in Task 5. They must satisfy the test and be a complete document.

`partials/header.php`:

```php
<?php
/** @var array $page */
$cfg = pd_config();
$title = htmlspecialchars($page['title'] ?? $cfg['site_name'], ENT_QUOTES);
$description = htmlspecialchars($page['description'] ?? '', ENT_QUOTES);
$extraCss = $page['extra_css'] ?? [];
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
<?php foreach ($extraCss as $href): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($href, ENT_QUOTES) ?>">
<?php endforeach; ?>
</head>
<body>
<a class="skip-link screen-reader-text" href="#main">Skip to content</a>
<div id="top-bar-wrap">
    <a href="tel:<?= htmlspecialchars($cfg['phone_tel'], ENT_QUOTES) ?>"><?= htmlspecialchars($cfg['phone'], ENT_QUOTES) ?></a>
    <span><?= htmlspecialchars($cfg['address'], ENT_QUOTES) ?></span>
</div>
<header id="site-header" role="banner">
    <a href="/">Piano Depot</a>
    <nav id="site-navigation" aria-label="Main website navigation">
        <a href="/pianos-we-sell/">PIANOS WE SELL</a>
        <a href="/contact-us/">CONTACT</a>
    </nav>
</header>
```

`partials/footer.php`:

```php
<?php
$cfg = pd_config();
?>
<footer id="footer" class="site-footer" role="contentinfo">
    <p><?= htmlspecialchars($cfg['site_name'], ENT_QUOTES) ?></p>
    <p><?= htmlspecialchars($cfg['address'], ENT_QUOTES) ?></p>
    <p><a href="tel:<?= htmlspecialchars($cfg['phone_tel'], ENT_QUOTES) ?>"><?= htmlspecialchars($cfg['phone'], ENT_QUOTES) ?></a></p>
</footer>
</body>
</html>
```

`index.php`:

```php
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'New & Used Pianos For Sale in Olyphant, PA | Piano Depot',
    'description' => 'Piano Depot in Olyphant, PA. New and used pianos, tuning, and service.',
    'extra_css' => [],
];
require $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr" role="main">
    <h1>Piano Depot</h1>
    <p>Clone in progress.</p>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
```

- [ ] **Step 4: Run tests and curl `/`**

Run: `php tests/run.php`

Expected: `All tests passed`.

Then (kill any stale `php -S localhost:8003` in this folder first):

```bash
php -S localhost:8003 router.php
```

In another terminal:

```bash
curl -s -o /tmp/pd-home.html -w "%{http_code}" http://localhost:8003/
echo
curl -s -o /tmp/pd-404.html -w "%{http_code}" http://localhost:8003/no-such-page/
echo
```

Expected: first request `200` and HTML contains `Piano Depot` and `Skip to content`. Second request `404` and HTML contains `Page not found`, `Home`, `Pianos we sell`, `Contact`.

- [ ] **Step 5: Commit**

```bash
git add partials/header.php partials/footer.php index.php tests/test_layout.php
git commit -m "Add stub header, footer, and homepage at /."
```

---

### Task 4: Scrape library — URL filter, path map, rewrite

**Files:**
- Create: `tools/lib.php`
- Create: `tests/test_tools_lib.php`

**Interfaces:**
- Consumes: nothing from the web on the unit tests (fixtures only)
- Produces:
  - `pd_should_clone(string $path): bool`
  - `pd_url_to_local_path(string $url, string $root): ?string` — filesystem path for a pianodepot.com or pianodepot.us URL; `null` if skip
  - `pd_rewrite_html(string $html): string` — strip live hosts to root-relative
  - `pd_excluded_path_prefixes(): array`

Excluded paths (exact, from the spec): `/cart/`, `/checkout/`, `/account/`, `/shop/`, `/home/`, `/home-11-1-23-holiday-promotion/`, and any path whose last slug ends in `-2`.

- [ ] **Step 1: Write the failing test**

Create `tests/test_tools_lib.php`:

```php
<?php

require_once dirname(__DIR__) . '/tools/lib.php';

expect(pd_should_clone('/') === true, 'clone home');
expect(pd_should_clone('/pianos-we-sell/') === true, 'clone catalog');
expect(pd_should_clone('/product/p-515/') === true, 'clone product');
expect(pd_should_clone('/cart/') === false, 'skip cart');
expect(pd_should_clone('/checkout/') === false, 'skip checkout');
expect(pd_should_clone('/account/') === false, 'skip account');
expect(pd_should_clone('/shop/') === false, 'skip shop');
expect(pd_should_clone('/home/') === false, 'skip old home');
expect(pd_should_clone('/home-11-1-23-holiday-promotion/') === false, 'skip holiday home');
expect(pd_should_clone('/disklavier-pianos-2/') === false, 'skip -2 duplicate');
expect(pd_should_clone('/gb1k-gc-series-5-to-5-8-2/') === false, 'skip another -2');
expect(pd_should_clone('/contact-us/') === true, 'clone contact');

$root = '/tmp/pd-root';
expect(pd_url_to_local_path('https://pianodepot.com/pianos-we-sell/', $root) === $root . '/pianos-we-sell/index.html', 'page path');
expect(pd_url_to_local_path('https://pianodepot.com/', $root) === $root . '/index.html', 'home page path');
expect(pd_url_to_local_path('https://pianodepot.com/wp-content/uploads/2021/03/piano_depot.png', $root) === $root . '/wp-content/uploads/2021/03/piano_depot.png', 'upload path');
expect(pd_url_to_local_path('http://pianodepot.us/wp-content/uploads/revslider/x.jpg', $root) === $root . '/wp-content/uploads/revslider/x.jpg', 'us-host upload');
expect(pd_url_to_local_path('https://fonts.googleapis.com/css?family=Cabin', $root) === null, 'skip google fonts');
expect(pd_url_to_local_path('https://player.vimeo.com/video/1', $root) === null, 'skip vimeo');

$html = pd_rewrite_html('<a href="https://pianodepot.com/contact-us/">x</a><img src="http://pianodepot.com/wp-content/uploads/a.jpg">');
expect(str_contains($html, 'href="/contact-us/"'), 'rewrite https host to root');
expect(str_contains($html, 'src="/wp-content/uploads/a.jpg"'), 'rewrite http host to root');
expect(!str_contains($html, 'pianodepot.com'), 'no pianodepot.com left in this snippet');
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL because `tools/lib.php` does not exist.

- [ ] **Step 3: Write minimal implementation**

Create `tools/lib.php` implementing the four functions so every assertion above passes. `pd_should_clone` treats a trailing-slash and no-trailing-slash the same. A slug “ends in `-2`” means the last path segment matches `/-2$` or `.*-2$` after trimming slashes (e.g. `disklavier-pianos-2`, not `b2` inside `sc2`). Use: the last segment matches `/.+-2$/`.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/run.php`

Expected: `All tests passed`.

- [ ] **Step 5: Commit**

```bash
git add tools/lib.php tests/test_tools_lib.php
git commit -m "Add scrape URL filter, local path map, and HTML host rewrite."
```

---

### Task 5: Download live pages and assets

**Files:**
- Create: `tools/scrape.php`
- Create: `PAGES.md` (generated)
- Modify: `tools/lib.php` (add `pd_extract_local_urls(string $htmlOrCss, string $baseUrl): array` if not already there)

**Interfaces:**
- Consumes: `pd_should_clone`, `pd_url_to_local_path`
- Produces: `scrape/raw/**` HTML for every cloned URL; `wp-content/**` (and `wp-includes/**` CSS/JS actually linked) on disk; `PAGES.md` listing every cloned path, one per line

- [ ] **Step 1: Write a focused test for asset URL extraction**

Append to `tests/test_tools_lib.php`:

```php
$found = pd_extract_local_urls(
    '<link href="https://pianodepot.com/wp-content/themes/oceanwp/assets/css/style.min.css?ver=1.0"><img src="/wp-content/uploads/2021/03/piano_depot.png">',
    'https://pianodepot.com/'
);
sort($found);
expect(in_array('https://pianodepot.com/wp-content/themes/oceanwp/assets/css/style.min.css', $found, true), 'css from link');
expect(in_array('https://pianodepot.com/wp-content/uploads/2021/03/piano_depot.png', $found, true), 'img src');
```

Also extract `url(...)` from CSS in the same function.

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL on `pd_extract_local_urls` undefined, or FAIL the new assertions.

- [ ] **Step 3: Implement extraction + scrape script**

`pd_extract_local_urls` returns absolute `https://pianodepot.com/...` (or pianodepot.us) URLs, query string stripped.

`tools/scrape.php` (CLI, no web access):

1. Fetch `https://pianodepot.com/page-sitemap.xml` and `https://pianodepot.com/product-sitemap.xml`.
2. Collect `<loc>` values. Keep those whose path `pd_should_clone` accepts. Always include `https://pianodepot.com/`.
3. Write `PAGES.md` starting with:

```markdown
# Cloned URLs

Serve from the project root:

    php -S localhost:8003 router.php

Then open http://localhost:8003/

```

followed by one `- /path/` bullet per cloned path.

4. For each page URL, download HTML to `scrape/raw{path}index.html` (home → `scrape/raw/index.html`). Retry up to 3 times; the live site is slow. Sleep 0.5s between pages.
5. Walk downloaded HTML (and then downloaded CSS) with `pd_extract_local_urls`. Download each local asset to `pd_url_to_local_path($url, PD_ROOT)` if the file does not already exist. Create parent directories. Do not download `/wp-admin/` or `/wp-json/`.
6. Print a summary: N pages, N assets, N skipped.

User-Agent: `PianoDepotClone/1.0`.

- [ ] **Step 4: Run tests, then scrape**

Run: `php tests/run.php`

Expected: pass.

Run: `php tools/scrape.php`

Expected: `PAGES.md` exists and contains `/`, `/contact-us/`, `/pianos-we-sell/`, `/product/p-515/`. Does not contain `/cart/` or `/account/`. `scrape/raw/index.html` exists. `wp-content/themes/oceanwp/assets/css/style.min.css` exists. `wp-content/uploads/2021/03/piano_depot.png` exists (logo).

- [ ] **Step 5: Commit**

```bash
git add tools/scrape.php tools/lib.php tests/test_tools_lib.php PAGES.md wp-content wp-includes
git commit -m "Scrape live Piano Depot pages and local assets."
```

Do not add `scrape/raw/` (gitignored). If `wp-content` is too large for one commit, still commit it — the clone cannot run without it.

---

### Task 6: Convert homepage into real partials

**Files:**
- Create: `tools/convert.php`
- Modify: `tools/lib.php` (split helpers)
- Modify: `partials/header.php`
- Modify: `partials/footer.php`
- Modify: `index.php`
- Create: `tests/test_split.php`

**Interfaces:**
- Consumes: scraped `scrape/raw/index.html`
- Produces:
  - `pd_split_oceanwp(string $html): array` with keys `title`, `description`, `extra_css`, `header` (from skip-link through `#site-header` inclusive, plus wrapping `#outer-wrap` / `#wrap` open tags that the footer will close), `main`, `footer` (from `#footer` through `</html>`)
  - Homepage `index.php` uses live `#main` HTML
  - Header/footer PHP files use live OceanWP chrome, with `$page` title/description/extra_css and `pd_config()` phone/address still present
  - ACCOUNT and cart/mini-cart nodes removed from the header HTML
  - Internal `pianodepot.com` hosts rewritten via `pd_rewrite_html`

Live split points (from the current site):

- `#wrap` children in order: `top-bar-wrap`, `site-header`, `main`, `footer`
- `#outer-wrap` wraps `#wrap`
- `#scroll-top` is a sibling under `body` — keep it in the footer partial
- Per-page Elementor CSS looks like `/wp-content/uploads/elementor/css/post-3068.css` — that is homepage `extra_css`, not shared

- [ ] **Step 1: Write the failing test**

Create `tests/test_split.php` using a **fixture string** (do not require the live scrape to unit-test):

```php
<?php

require_once dirname(__DIR__) . '/tools/lib.php';

$fixture = <<<HTML
<!DOCTYPE html><html><head><title>Home Title</title>
<meta name="description" content="Home desc">
<link rel="stylesheet" href="https://pianodepot.com/wp-content/themes/oceanwp/assets/css/style.min.css">
<link rel="stylesheet" href="https://pianodepot.com/wp-content/uploads/elementor/css/post-3068.css">
</head><body>
<a class="skip-link" href="#main">Skip to content</a>
<div id="outer-wrap"><div id="wrap">
<div id="top-bar-wrap">phone</div>
<header id="site-header"><nav><a href="https://pianodepot.com/account/">ACCOUNT</a>
<a class="wcmenucart" href="https://pianodepot.com/cart/">0</a>
<a href="https://pianodepot.com/pianos-we-sell/">PIANOS WE SELL</a>
</nav></header>
<main id="main"><h1>Hello</h1></main>
<footer id="footer">foot</footer>
</div></div>
<a id="scroll-top" href="#top">Top</a>
</body></html>
HTML;

$parts = pd_split_oceanwp($fixture);
expect($parts['title'] === 'Home Title', 'title');
expect($parts['description'] === 'Home desc', 'description');
expect(str_contains($parts['main'], '<h1>Hello</h1>'), 'main body');
expect(str_contains($parts['header'], 'id="site-header"'), 'header has site-header');
expect(str_contains($parts['header'], 'id="top-bar-wrap"'), 'header has top bar');
expect(!str_contains($parts['header'], '/account/'), 'account stripped');
expect(!str_contains($parts['header'], '/cart/'), 'cart stripped');
expect(str_contains($parts['header'], '/pianos-we-sell/'), 'catalog link kept');
expect(str_contains($parts['footer'], 'id="footer"'), 'footer');
expect(str_contains($parts['footer'], 'scroll-top'), 'scroll-top in footer');
expect(in_array('/wp-content/uploads/elementor/css/post-3068.css', $parts['extra_css'], true), 'page extra css');
expect(!in_array('/wp-content/themes/oceanwp/assets/css/style.min.css', $parts['extra_css'], true), 'shared css is not extra');
```

Add `pd_is_shared_stylesheet(string $href): bool` — true for theme/plugin CSS that is not `uploads/elementor/css/post-{digits}.css`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL on missing `pd_split_oceanwp`.

- [ ] **Step 3: Implement split + convert homepage**

Implement `pd_split_oceanwp` and `pd_is_shared_stylesheet` in `tools/lib.php`. Strip any `a[href*="/account/"]` and mini-cart/cart links (`wcmenucart`, `href` containing `/cart/`) from the header HTML only.

`tools/convert.php` CLI:

- Args: none, or a single path to convert. Default: all files under `scrape/raw/` whose URL is in `PAGES.md`.
- First time / homepage: write `partials/header.php` and `partials/footer.php` from the split, wrapping the live chrome with the `$page` title/description/extra_css loop and `require` of config (header already assumes config was required by the page).
- Shared `<link rel="stylesheet">` and shared `<script src>` from the homepage `<head>` / end of body go into header/footer respectively (footer gets the scripts that were after `#footer`). Google Fonts links stay as remote URLs.
- Each page file is written as:

```php
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => '...',
    'description' => '...',
    'extra_css' => [ /* per-page elementor post-N.css paths, root-relative */ ],
];
require $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
MAIN_HTML
<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
```

- MAIN_HTML is `$parts['main']` after `pd_rewrite_html`.
- Header/footer stored HTML is also rewritten.

Run: `php tools/convert.php /`

That must replace stub header/footer/index.php with the live homepage.

- [ ] **Step 4: Run tests and curl the real homepage**

Run: `php tests/run.php`

Expected: pass (layout test still passes: skip link, phone, address, extra_css — the live header still contains those strings).

If the layout test fails because the live header uses a logo `<img>` instead of the text “Piano Depot”, update `tests/test_layout.php` to assert the logo `alt` or `/wp-content/uploads/2021/03/piano_depot.png` instead of the text link. Do not weaken phone/address/skip-link assertions.

Curl:

```bash
php -S localhost:8003 router.php
curl -s http://localhost:8003/ | head -c 500
```

Expected: 200. HTML includes `id="site-header"`, `id="main"`, Yamaha/piano copy from the live home, and stylesheet hrefs that start with `/wp-content/` (not `https://pianodepot.com/wp-content`).

In the browser at `http://localhost:8003/`: homepage should look like pianodepot.com. Note remaining gaps (missing assets) and fix scrape for those URLs before moving on.

- [ ] **Step 5: Commit**

```bash
git add tools/convert.php tools/lib.php tests/test_split.php tests/test_layout.php partials/header.php partials/footer.php index.php
git commit -m "Convert homepage into shared OceanWP header and footer."
```

---

### Task 7: Convert remaining content pages

**Files:**
- Modify: `tools/convert.php` (loop all PAGES.md entries that are not `/product/...`)
- Create: one `{slug}/index.php` per content URL
- Modify: `404.php` to use the real header/footer (remove the stub `is_file` branches)

**Interfaces:**
- Consumes: `pd_split_oceanwp`, scraped HTML, existing header/footer (do not overwrite header/footer after homepage)
- Produces: a directory `index.php` for every non-product path in `PAGES.md`

- [ ] **Step 1: Write a failing test that convert is idempotent for a nested path**

Append to `tests/test_split.php`:

```php
$written = pd_page_php(
    [
        'title' => 'Contact Piano Depot | Piano Store in Olyphant, PA',
        'description' => 'Call us',
        'extra_css' => ['/wp-content/uploads/elementor/css/post-11.css'],
        'main' => '<main id="main">contact body</main>',
    ]
);
expect(str_contains($written, "require \$_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';"), 'config include');
expect(str_contains($written, "require \$_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';"), 'header include');
expect(str_contains($written, "require \$_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';"), 'footer include');
expect(str_contains($written, 'contact body'), 'body');
expect(str_contains($written, "post-11.css"), 'extra css');
```

Add `pd_page_php(array $parts): string` in `tools/lib.php`.

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL until `pd_page_php` exists.

- [ ] **Step 3: Implement `pd_page_php` and convert all non-product pages**

`tools/convert.php` with no args converts every `PAGES.md` path. Skip writing `partials/header.php` / `footer.php` unless `--write-chrome` is passed (homepage Task 6 used that once).

Run: `php tools/convert.php`

- [ ] **Step 4: Run tests and spot-check three URLs**

Run: `php tests/run.php`

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8003/contact-us/
echo
curl -s -o /dev/null -w "%{http_code}" http://localhost:8003/pianos-we-sell/
echo
curl -s -o /dev/null -w "%{http_code}" http://localhost:8003/our-history/
echo
```

Expected: `200` for each. Each body includes `id="site-header"` and `id="footer"`. Contact page includes the Gravity Form markup (`gform_2` or the form fields).

Update `404.php` to the same include pattern as other pages (no stub branches).

- [ ] **Step 5: Commit**

```bash
git add -A
git reset scrape/raw 2>/dev/null || true
git commit -m "Convert remaining content pages to PHP includes."
```

---

### Task 8: Convert product pages and replace add-to-cart

**Files:**
- Modify: `tools/lib.php` — `pd_replace_add_to_cart(string $html, array $cfg): string`
- Create: `tests/test_product.php`
- Create/replace: `product/{slug}/index.php` for every `/product/...` in `PAGES.md`

**Interfaces:**
- Consumes: `pd_config()` for phone and `phone_tel`; scraped product HTML
- Produces: product pages that keep photos, copy, and price; no quantity/add-to-cart form; visible “Call or text 570-352-5501” and a link to `/contact-us/`

- [ ] **Step 1: Write the failing test**

Create `tests/test_product.php`:

```php
<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/tools/lib.php';

$cfg = pd_config();
$html = <<<HTML
<form class="cart" action="https://pianodepot.com/product/p-515/" method="post">
  <input type="number" name="quantity" value="1">
  <button type="submit" name="add-to-cart" value="123" class="single_add_to_cart_button">Add to cart</button>
</form>
<p class="price"><span class="woocommerce-Price-amount">$1,299.00</span></p>
HTML;

$out = pd_replace_add_to_cart($html, $cfg);
expect(!str_contains($out, 'Add to cart'), 'button gone');
expect(!str_contains(strtolower($out), 'name="quantity"'), 'quantity gone');
expect(str_contains($out, '570-352-5501'), 'phone shown');
expect(str_contains($out, 'tel:+15703525501'), 'tel link');
expect(str_contains($out, '/contact-us/'), 'contact link');
expect(str_contains($out, '$1,299.00'), 'price kept');
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL on undefined `pd_replace_add_to_cart`.

- [ ] **Step 3: Implement replacement and convert products**

`pd_replace_add_to_cart` removes `form.cart` (and WooCommerce add-to-cart wrappers if present) and inserts:

```html
<p class="pd-call-to-buy">Call or text <a href="tel:+15703525501">570-352-5501</a> or <a href="/contact-us/">contact us</a> about this piano.</p>
```

`tools/convert.php` runs this on any path starting with `/product/` after split/rewrite.

Run: `php tools/convert.php`

This replaces the Task 2 placeholder `product/p-515/index.php` with the real clone.

- [ ] **Step 4: Run tests and curl a nested product**

Run: `php tests/run.php`

```bash
curl -s -o /tmp/pd-p515.html -w "%{http_code}" http://localhost:8003/product/p-515/
echo
```

Expected: `200`. File contains `570-352-5501` and `/contact-us/`. Does not contain `Add to cart`. Contains `require` of header via rendered chrome (`id="site-header"`). Nested include works (no PHP warning about missing partials).

- [ ] **Step 5: Commit**

```bash
git add tools/lib.php tools/convert.php tests/test_product.php product
git commit -m "Clone product pages and replace add-to-cart with call/text."
```

---

### Task 9: Forms

**Files:**
- Create: `partials/form.php`
- Create: `forms/send.php`
- Create: `tests/test_form.php`
- Modify: converted `contact-us/index.php`, `piano-moving-form/index.php`, `apply-for-credit-at-pianodepot-com/index.php` (or do this in convert: rewrite Gravity Form `action` to `/forms/send.php` and inject honeypot + `?sent=` banner)

**Interfaces:**
- Consumes: `pd_config()['email_to']`, `$_POST`, `$_SERVER['HTTP_REFERER']` (fallback `/contact-us/`)
- Produces:
  - `pd_validate_form(array $post): array` — `['status' => 'ok'|'error'|'honeypot', 'errors' => string[], 'subject' => string, 'body' => string]`
  - `forms/send.php` — POST only; honeypot → redirect `?sent=1`; validation fail → redirect `?error=1`; `mail()` true → `?sent=1`; `mail()` false → `?mail=0`
  - Pages show a success banner when `?sent=1`, a fill-in message when `?error=1`, and “could not send — please call or text 570-352-5501” with a `tel:` link when `?mail=0`

Live Contact fields (Gravity Forms form 2):

- `input_1.3` First name
- `input_1.6` Last name
- `input_2` Email
- `input_3` Message
- `input_4` extra “Name” (GF honeypot) — if filled, treat as honeypot

Required for our validator (spec minimum): first or last name, email, message.

Also add a hidden field `website` (CSS-hidden). If filled, honeypot.

Hidden `pd_form` value: `contact` | `moving` | `credit` for the email subject.

- [ ] **Step 1: Write the failing test**

Create `tests/test_form.php`:

```php
<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/partials/form.php';

$ok = pd_validate_form([
    'input_1.3' => 'Ada',
    'input_1.6' => 'Lovelace',
    'input_2' => 'ada@example.com',
    'input_3' => 'Need a piano',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($ok['status'] === 'ok', 'valid contact');
expect(str_contains($ok['body'], 'Ada'), 'body has name');
expect(str_contains($ok['subject'], 'Contact'), 'subject');

$bad = pd_validate_form([
    'input_1.3' => '',
    'input_1.6' => '',
    'input_2' => '',
    'input_3' => '',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($bad['status'] === 'error', 'empty is error');
expect(!empty($bad['errors']), 'errors listed');

$hp = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'website' => 'http://spam.example',
    'pd_form' => 'contact',
]);
expect($hp['status'] === 'honeypot', 'website honeypot');

$hp2 = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'input_4' => 'filled',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($hp2['status'] === 'honeypot', 'input_4 honeypot');
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/run.php`

Expected: FAIL because `partials/form.php` does not exist.

- [ ] **Step 3: Implement validator and `forms/send.php`**

`partials/form.php` implements `pd_validate_form` as specified.

`forms/send.php`:

```php
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/form.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /contact-us/', true, 302);
    exit;
}

$cfg = pd_config();
$result = pd_validate_form($_POST);
$back = '/contact-us/';
$ref = $_POST['pd_redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '');
if (is_string($ref) && str_starts_with(parse_url($ref, PHP_URL_PATH) ?? '', '/')) {
    $path = parse_url($ref, PHP_URL_PATH);
    if (is_dir(PD_ROOT . rtrim($path, '/')) || $path === '/') {
        $back = $path;
    }
}

if ($result['status'] === 'honeypot') {
    header('Location: ' . $back . (str_contains($back, '?') ? '&' : '?') . 'sent=1', true, 302);
    exit;
}

if ($result['status'] === 'error') {
    header('Location: ' . $back . '?error=1', true, 302);
    exit;
}

$sent = mail(
    $cfg['email_to'],
    $result['subject'],
    $result['body'],
    'From: ' . $cfg['email_to'] . "\r\n" . 'Content-Type: text/plain; charset=UTF-8'
);

header('Location: ' . $back . ($sent ? '?sent=1' : '?mail=0'), true, 302);
exit;
```

On the three form pages, after convert:

1. Set the form `action` to `/forms/send.php` and `method="post"`.
2. Add `<input type="hidden" name="pd_form" value="contact">` (or `moving` / `credit`).
3. Add `<input type="hidden" name="pd_redirect" value="/contact-us/">` (matching path).
4. Add honeypot `website` input with inline style `position:absolute;left:-9999px`.
5. In `#main`, if `($_GET['sent'] ?? '') === '1'`, print a paragraph “We got it — we will call you.”
6. If `error=1`, print “Please fill this in: name, email, and message.”
7. If `mail=0`, print `could not send — please call or text 570-352-5501` with `<a href="tel:+15703525501">`.

Inspect moving and credit Gravity Form field names after scrape. Map them in `pd_validate_form` the same way: require a name-like field plus email or phone plus a message/details field. Put the mapping in `partials/form.php` (no “handle later”).

Prefer adding a convert pass `pd_wire_form(string $html, string $path): string` so re-running convert does not wipe the wiring.

- [ ] **Step 4: Run tests and POST the contact form**

Run: `php tests/run.php`

```bash
curl -s -o /dev/null -w "%{http_code} %{redirect_url}" -X POST http://localhost:8003/forms/send.php \
  -d "input_1.3=" -d "input_1.6=" -d "input_2=" -d "input_3=" -d "website=" -d "pd_form=contact" -d "pd_redirect=/contact-us/"
echo
curl -s -o /dev/null -w "%{http_code} %{redirect_url}" -X POST http://localhost:8003/forms/send.php \
  -d "input_1.3=Test" -d "input_1.6=User" -d "input_2=test@example.com" -d "input_3=Hello" -d "website=" -d "pd_form=contact" -d "pd_redirect=/contact-us/"
echo
```

Expected: first POST redirects to `/contact-us/?error=1`. Second POST redirects to `/contact-us/?sent=1` or `/contact-us/?mail=0` depending on whether local `mail()` works. Never show success HTML on a `mail=0` redirect.

- [ ] **Step 5: Commit**

```bash
git add partials/form.php forms/send.php tests/test_form.php tools/lib.php contact-us piano-moving-form apply-for-credit-at-pianodepot-com
git commit -m "Wire contact, moving, and credit forms to PHP mail."
```

---

### Task 10: Verification against the spec

**Files:**
- Create: `tests/test_pages.php` (HTTP checks against localhost)
- Modify: `PAGES.md` if any URL was skipped by accident

**Interfaces:**
- Consumes: running `php -S localhost:8003 router.php`
- Produces: a green `php tests/run.php` plus a curl/browser checklist that matches the spec “Must pass before v1 is done”

- [ ] **Step 1: Write HTTP checks**

`tests/test_pages.php` should **skip** (print `SKIP: start php -S localhost:8003 router.php`) if `http://127.0.0.1:8003/` is not reachable, so `php tests/run.php` still works offline. When reachable:

```php
function pd_http_get(string $path): array
{
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'ignore_errors' => true, 'follow_location' => 0]]);
    $body = @file_get_contents('http://127.0.0.1:8003' . $path, false, $ctx);
    $code = 0;
    if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
        $code = (int) $m[1];
    }
    return ['code' => $code, 'body' => $body === false ? '' : $body];
}
```

Assert:

- `GET /` → 200, contains `id="site-header"`, contains `id="footer"`, does **not** contain `https://pianodepot.com/wp-content`
- `GET /pianos-we-sell/` → 200, contains `id="site-header"`
- `GET /product/p-515/` → 200, contains `id="site-header"`, contains `570-352-5501`, does not contain `Add to cart`
- `GET /contact-us/` → 200, contains `forms/send.php` or `gform`
- `GET /no-such-page-xyz/` → 404, contains `Page not found`, contains `Pianos we sell`
- `GET /account/` → 404
- `GET /cart/` → 404

- [ ] **Step 2: Run tests without server (skip), then with server (pass)**

Run: `php tests/run.php`

Expected: layout/unit tests pass; HTTP tests print SKIP if the server is down.

Start `php -S localhost:8003 router.php` if needed. Re-run `php tests/run.php`.

Expected: HTTP assertions PASS.

- [ ] **Step 3: Browser pass (required by the spec)**

Open:

1. `http://localhost:8003/` desktop and a phone-width viewport
2. `http://localhost:8003/product/p-515/` desktop and phone-width
3. Click main nav items — they must stay on localhost, not pianodepot.com
4. View-source on home: CSS `href` values are `/wp-content/...` or Google Fonts, not `https://pianodepot.com/wp-content`

If a stylesheet 404s, download it with the scrape tool and re-run convert only if HTML references changed.

Tick every path in `PAGES.md` with curl (200). Any 404 that should exist is a bug in convert; fix and re-run `php tools/convert.php`.

- [ ] **Step 4: Commit**

```bash
git add tests/test_pages.php PAGES.md
git commit -m "Add live HTTP checks for home, product, contact, and 404."
```

---

## Self-review (plan vs spec)

| Spec item | Task |
| --- | --- |
| PHP includes, no DB, no Laravel | Tasks 1–3 |
| `/` is `index.php`, no `/public` | Tasks 3, 10 |
| `DOCUMENT_ROOT` includes | Tasks 3, 7, 8 |
| Shared header/nav/footer | Tasks 3, 6 |
| `config.php` phone/address/email/`display_errors` | Task 1 |
| Clone content + products, skip cart/account/shop/old homes/`-2` | Tasks 4, 7, 8 |
| Original `/wp-content` paths, no live WP assets | Tasks 4, 6, 10 |
| Replace add-to-cart with call/text | Task 8 |
| Gravity Forms → `forms/send.php`, honeypot, `?sent=1`, mail failure copy | Task 9 |
| `404.php` + `.htaccess` + `router.php` | Task 2 |
| `PAGES.md` checklist | Tasks 4, 10 |
| Desktop + phone visual match | Task 10 |
| Nested `/product/{slug}/` includes | Tasks 2, 8, 10 |

No TBD/TODO remaining. `email_to` defaults to `info@pianodepot.com` in config (Frank can change one line). Moving/credit field mapping is required inside Task 9, not deferred.
