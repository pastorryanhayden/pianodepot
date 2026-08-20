<?php
http_response_code(404);
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Page not found | Piano Depot',
    'description' => 'That page is not on Piano Depot.',
    'extra_css' => [],
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
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
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
