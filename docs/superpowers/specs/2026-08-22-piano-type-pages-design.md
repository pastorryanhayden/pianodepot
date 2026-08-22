# Piano type pages catalog rebuild

Date: 2026-08-22
Status: Draft for review

## Goal

Bring the leftover piano-type pages up to the visual standard of the rebuilt site (Clavinova catalog, section landings, footer-first chrome). Visitors should land on a type, understand what it is, see the models Piano Depot actually lists, watch the useful videos, and book an appointment.

Disklavier is the first page. The same template then covers the other leftover types.

## Scope

In:

- `/disklavier-pianos/`
- `/acoustic-grand-pianos/`
- `/acoustic-silent-trans-acoustic-pianos/`
- `/acoustic-upright-pianos/`
- `/portable-digital-pianos/`
- `/workstation-keyboards/`
- `/used-and-refurbished/`

Out:

- `/clavinova-and-hybrid-pianos/` and `/clavinova-sale/` (already rebuilt)
- `/piano-closeout-sales-in-olyphant-pa/` (already a custom product list)
- Individual `/product/…` pages
- Used subcategory listings (`/used-refurbished-grand-pianos/` and siblings)
- Researching current Yamaha MAP prices or adding models that are not already on the page
- Pushing `main` (live deploy) unless the user explicitly asks

## Approach

Shared catalog template plus a `$catalog` array on each type page. Same pattern as Clavinova, not a CSS restyle of the scraped WordPress markup, and not a single mega-config for all types.

Before replacing a type `index.php`, copy it to `index.legacy.php` in the same folder. That file is not linked in the nav. Restoring a page is copying it back over `index.php`. Git history is a second copy.

## Architecture

URLs stay as directories with `index.php`. Shared chrome stays `partials/header.php` and `partials/footer.php`.

New files:

- `partials/piano-type-catalog.php` — the only renderer for these seven pages
- `wp-content/uploads/piano-type-pages.css` — visual language for this template only, so Clavinova CSS is not at risk

Each public type page becomes:

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => '…',
    'description' => '…',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [ /* page content */ ];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
```

The public file does not include Revolution Slider, WPBakery, Elementor, WooCommerce product loops, Quick View, or Gravity Forms markup.

`partials/pianos-category-nav.php` stays the collection switcher. It marks the current type (red fill on the current chip) using the request path or a `$catalog['nav']` slug.

## Components

Top to bottom on every type page:

1. Existing site header
2. Existing cream collection nav, current type marked
3. Photo hero using that type’s current slider image, darkened: eyebrow, H1, one short intro, primary **Schedule an Appointment** (`/contact-us/`), optional secondary Yamaha catalog link
4. In-page group chips only when `$catalog['groups']` is used (Used & Refurbished). Flat model lists have no chip row — the cards are the series.
5. Model cards: 3 columns on desktop, 2 on tablet, 1 on phone. Photo, label, name, one-line description, link to the local product page. No prices unless the current page already shows one. No Quick View
6. Videos only when `$catalog['videos']` is non-empty: 16:9 grid, captions, no duplicate embeds
7. Dark CTA band: appointment copy, `/contact-us/`, phone/text 570-352-5501
8. Existing footer

Used & Refurbished uses the same stack with `groups` (grand, upright, console, spinet, digital) instead of a flat model list. Its warehouse photos sit as a small gallery above the cards.

Visual tokens match the rebuilt site: cream `#f6f3ed`, piano red `#b11f24`, charcoal `#171717` / `#252525`, Clavinova-style cards. The photo hero is the signature: each type keeps its own instrument photograph rather than sharing Clavinova’s gradient.

### Removed from the public pages

- Revolution Slider heroes (the photograph is reused; the slider JS is not)
- Duplicate black `piano_internal-links` nav
- `piano_series-links` as a separate WP list (replaced by chips or cards)
- WooCommerce product grid, Quick View, and product search
- Sidebar Gravity Form (appointment is `/contact-us/`, same as Clavinova)
- Duplicate video rows
- Time-expired promo copy (the grand-page 0% APR window that ended 2025-01-06)
- Yamaha “CLICK HERE FOR DETAILS” buttons that point at the wrong category (the grand page currently links to Silent Piano)

## Data flow

`$catalog` is owned by each type `index.php`. The partial only renders.

Required keys:

- `nav` — slug matching a collection-nav href (for the current chip)
- `eyebrow`, `title`, `intro`
- `hero_image` — existing local `/wp-content/uploads/…` path
- `models` and/or `groups` — at least one model to show

Optional keys, omitted when empty:

- `yamaha_href`, `yamaha_label` — only when the URL is actually for this type
- `series` — chips (`id`, `label`, `href`)
- `videos` — `id` (YouTube), `caption`
- `gallery` — extra photos (used warehouse shots)
- `cta_title`, `cta_text`, `cta_action` — defaults if omitted: appointment language and `/contact-us/`

Model card fields: `name`, `href` (local product URL), `image`, `description`, optional `label`.

Copy comes from the current type page, de-duplicated. The Disklavier slider paragraph and body paragraph are the same text today; the new page uses it once. Do not invent MAP prices or new models.

### Models and videos to keep

**Disklavier** — hero `/wp-content/uploads/2021/06/vA-6hReg.jpeg`

- Enspire CL → `/product/enspire-cl/`
- Enspire ST → `/product/enspire-st/`
- Enspire Pro → `/product/enspire-pro/`
- DKC-900 Upgrade Kit → `/product/dkc-900-upgrade-kit/`
- Yamaha catalog: `https://usa.yamaha.com/products/musical_instruments/pianos/disklavier/index.html`
- Videos (unique ids only): `T9Qv5B1Eb-k`, `U1cNpWSI9Nw`, `Xu36GOKXs5M`, `kvUFUKFUDC4`, `hlvBms8IW7o`

**Acoustic Grand** — hero `/wp-content/uploads/2021/06/j2glbwww.jpeg`

- GB1K/GC Series → `/product/gb1k-gc-series-5-to-5-8/`
- CX Series → `/product/cx-series-5-3-to-7-6/`
- SX Series → `/product/sx-series-6-1-to-7-6/`
- CF Series → `/product/cf-series-6-3-to-9/`
- Video: `tTg2D3rFdcw`

**Silent & TransAcoustic** — hero `/wp-content/uploads/2021/06/duoXPjwM.jpeg`

- TA2 TransAcoustic → `/product/ta2-transacoustic/`
- SC2 Silent → `/product/sc2-silent-piano/`
- SH2 Silent → `/product/sh2-silent-piano/`
- Video: `Xu36GOKXs5M`

**Acoustic Upright** — hero `/wp-content/uploads/2021/06/IgVWKrB0.jpeg`

- b Series → `/product/b-series/`
- P22 → `/product/p22-piano/`
- U Series → `/product/u-series/`
- YUS Series → `/product/yus-series/`
- Video: `fKGu54YKkSE`

**Portable Digital** — hero `/wp-content/uploads/2021/06/CpNzSo3U.jpeg`

- P-515 → `/product/p-515/`
- P-125 → `/product/p-125/`
- P-121 → `/product/p-121/`
- P-45 → `/product/p-45/`
- DGX-670 → `/product/dgx-670-portable-grand-piano/`
- Video: `WqSXOM49GZA`

**Workstations** — hero `/wp-content/uploads/2021/06/wsm9Gy8I.jpeg`

- PSR-SX600 → `/product/psr-sx600/`
- PSR-SX700 → `/product/psr-sx700/`
- PSR-SX900 → `/product/psr-sx900/`
- Genos → `/product/genos/`
- Video: `32AdjKpF8b4`

**Used & Refurbished** — hero `/wp-content/uploads/2021/06/XVVoBzWk.jpeg`

- Group chips to the five subcategory pages: restored grand, upright, console, spinet, digital
- Product cards already on the landing page: Kimball, Knabe & Co, Story & Clark (Player), Yamaha U3, Young Chang F-110, Baldwin Grand, Baldwin 243 SB, Baldwin Howard, Hyundai U810, Kawai, Kimball Artist Console, Shaw, Winter DarkOak Cabinet, Yamaha M214B W, Yamaha GH1
- Warehouse photos already on the page as `gallery`
- Video: `Cz3oCAp2Nts`

One-line card descriptions come from existing product titles or the short lines already on the type page. Blank is allowed; do not write new marketing copy.

## Error handling

These pages are static. There is no catalog API.

- A model with a missing `image` still renders; the image area is empty, the grid does not break
- A video with an empty YouTube id is skipped; other videos still render
- Appointment always goes to `/contact-us/`
- `index.legacy.php` is reachable only by filename, not from the nav
- A wrong local product `href` is a content bug in that page’s array, caught by tests, not by runtime recovery

## Testing

Keep `php tests/run.php`. Add `tests/test_piano_type_pages.php` using the existing HTTP helper (it currently probes port 8003 and skips if that server is not running).

For each of the seven URLs:

- Response is 200
- Body includes the hero title, collection nav, and appointment CTA
- Body does not include `rs-module-wrap`, `owp-quick-view`, `wc-block-product-search`, or `piano_internal-links`

Also:

- Disklavier includes the five unique YouTube ids and does not repeat them
- Each rebuilt folder contains `index.legacy.php`
- `git diff --check` is clean on the task files

Visual check is local: Disklavier first, then the other six, desktop and a narrow viewport. No browser automation in CI.

## Build order

1. Save all seven `index.legacy.php` copies
2. Shared CSS + partial
3. Disklavier as the first public page
4. Remaining six types
5. Tests and visual pass

## Rollback

To restore a type if Frank prefers the old page: copy `index.legacy.php` over `index.php` in that folder. The shared partial and CSS can stay; unused files do not affect other pages.
