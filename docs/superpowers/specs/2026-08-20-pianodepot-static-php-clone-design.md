# Piano Depot static PHP clone

Date: 2026-08-20
Status: Draft for review
Source: pianodepot.com (WordPress + OceanWP + Elementor + WooCommerce)

## Goal

Replace the slow WordPress site with a visual clone Frank Bissol can host and hand to an AI agent. Same URLs, same HTML and CSS, no database, no Laravel.

PHP is used only for shared header/nav/footer and form mail. Each page is otherwise static HTML.

## Stack choice

Plain PHP includes. Not Laravel, not a static-site generator, not duplicated headers in every file.

Laravel without a database is ceremony Frank does not need (Composer, APP_KEY, a host that can run Laravel). He already shipped a simple HTML site at freescripturesongs.com. Three include files give the same menu reuse and run on any cheap PHP host.

## Architecture

The web root is the project root. The homepage is `/`, served by `/index.php`. There is no `/public` subdirectory and no app prefix.

Each current public URL becomes a directory with `index.php` so WordPress permalinks keep working without rewrite rules:

```
/
  index.php                          → /
  pianos-we-sell/index.php           → /pianos-we-sell/
  contact-us/index.php               → /contact-us/
  product/p-515/index.php            → /product/p-515/
  404.php
  router.php
  partials/config.php
  partials/header.php
  partials/footer.php
  forms/send.php
  wp-content/                        → copied CSS, JS, fonts, images
  docs/superpowers/specs/
  PAGES.md
  .htaccess
```

Every page file:

```php
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => '…',
    'description' => '…',
];
require $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<!-- unique body HTML copied from the live page -->
<?php require $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
```

`$_SERVER['DOCUMENT_ROOT']` is required so nested product paths include the same partials as the homepage.

### Local serve

From the project root:

```bash
php -S localhost:8003 router.php
```

Then open `http://localhost:8003/` — path `/`, not a subdirectory. Port 8003 is the default here because 8000 is often already taken on this machine. Any free port is fine; the path is always `/`.

`router.php` is only for PHP’s built-in server (it ignores `.htaccess`). It serves real files and directories as usual and sends unknown paths to `404.php`. Apache production uses `.htaccess` instead.

### Production

Point the vhost document root at this project folder. Homepage is `https://pianodepot.com/`.

## Components

### Partials

- `partials/config.php` — site name, phone (`570-352-5501`), address, recipient email for forms, `display_errors` off. The one file an agent edits for business facts.
- `partials/header.php` — `<head>` (title/description from `$page`), skip link, top phone/address bar, logo, main nav.
- `partials/footer.php` — four footer columns, social, copyright, scripts.

### Pages we clone (same slugs as WordPress)

- Home
- Catalog category pages: acoustic grand, acoustic upright, Disklavier, silent/trans, Clavinova/hybrid, portable digital, workstation keyboards, used/refurbished and its subpages
- About, history, contact, services, pianos we buy, organs we buy, warranties, privacy, credit application, piano moving form, tuning/refurbishing, closeout
- Articles of interest currently on the sitemap
- Spec/comparison sheets currently on the sitemap
- Each current `/product/{slug}/` page as a static catalog card (photos, copy, price)

`PAGES.md` is the living URL checklist, taken from the live sitemaps minus the exclusions below.

### Product pages

Keep photos, copy, and price. Replace WooCommerce “Add to cart” / quantity with “Call or text 570-352-5501” and a link to Contact. No cart session, no checkout.

### We do not clone

- `/cart/`, `/checkout/`, `/account/`, live WooCommerce shop
- Old or duplicate homes (`/home/`, holiday promo homes, `-2` duplicate slugs)
- WordPress, Elementor, or plugin admin
- pianoorgandepot.com (footer links that already go there stay external)

### Assets

Copy the CSS, JS, fonts, and images the pages actually load. Keep original paths (`/wp-content/uploads/...`, theme and plugin CSS URLs) so body HTML is a near-paste of the live markup. The new host must not request CSS or images from the live WordPress server.

External embeds (Vimeo, YouTube, Google Maps, Facebook) stay as remote URLs.

### How we get the HTML

Download the live pages. Cut the chrome into `header.php` and `footer.php`. Leave the unique body in each page’s `index.php`. Visual match, not a redesign.

## Data flow

GET: the server maps the URL to a folder’s `index.php` (or `/index.php` for `/`). Includes run, HTML goes out. No database, no session, no user accounts.

POST: only the three live forms (Contact, Piano moving, Apply for credit). They keep their current HTML and post to `forms/send.php`, which:

1. Rejects empty honeypot (treat as success, do not tip off bots)
2. Requires the same fields the live form marks required (at minimum name plus phone or email)
3. Emails the recipient in `config.php`
4. Redirects back to the same page with `?sent=1` so the page can show a short confirmation

No stored submissions. If host `mail()` is later unreliable, swap `forms/send.php` only (e.g. Formspree). Product “buy” is not a flow: phone number + Contact link.

## Error handling

- Unknown URLs: `404.php` with the real header/footer, short copy, and links to Home, Pianos we sell, and Contact. `.htaccess` `ErrorDocument 404 /404.php` (Apache). Nginx equivalent is a one-line `error_page` in the vhost, not in this repo.
- Form validation errors: stay on the same page, inline “please fill this in”.
- `mail()` failure: “could not send — please call or text 570-352-5501” with a `tel:` link. Never show success if the email did not send.
- PHP: `display_errors` off via `config.php`.

## Testing

Visual clone, not a unit-test suite. Serve from project root (`php -S localhost:8003`) and hit `/`.

Must pass before v1 is done:

- Home (`/`), one catalog category, one nested product (`/product/{slug}/`), Contact, and 404 all render with the shared header/footer
- Nested product includes do not break (DOCUMENT_ROOT includes)
- Main nav and footer internal links go to cloned pages, not pianodepot.com
- CSS and images load from this project, not the live WordPress host
- Contact form: empty submit shows validation; valid submit shows success (test recipient in `config.php`)
- Desktop and phone-width viewport on Home and one product page match the live layout closely

Tick cloned URLs against `PAGES.md`.

## Out of scope (v1)

- Database or CMS
- Laravel
- Working cart/checkout/account
- Combining pianoorgandepot.com into this site
- Redesign, new branding, or new copy except replacing add-to-cart with call/text

## Success

Frank can point a PHP host at this folder, open `/`, see the current site, change a menu once in `header.php`, and hand the files to an agent for later content edits.
