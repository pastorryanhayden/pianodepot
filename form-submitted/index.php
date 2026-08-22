<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Thank You | Piano Depot',
    'description' => 'Your message has been sent to Piano Depot.',
    'extra_css' => ['/wp-content/uploads/section-detail-pages.css'],
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr" role="main">
    <div id="content-wrap" class="container clr">
        <article class="single-page-article clr">
            <div class="entry clr" itemprop="text">
                <h1>Thank You</h1>
                <h2>Your message was sent successfully.</h2>
                <p>We have received your information and will contact you as soon as possible.</p>
                <p>If your request is urgent, please call or text <a href="tel:+15703525501">570-352-5501</a>.</p>
                <p><a class="vc_btn3" href="/">Return to Piano Depot</a></p>
            </div>
        </article>
    </div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
