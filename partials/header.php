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
