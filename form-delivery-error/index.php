<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Message Not Sent | Piano Depot',
    'description' => 'There was a problem delivering your message to Piano Depot.',
    'extra_css' => ['/wp-content/uploads/section-detail-pages.css'],
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
?>
<main id="main" class="site-main clr" role="main">
    <div id="content-wrap" class="container clr">
        <article class="single-page-article clr">
            <div class="entry clr" itemprop="text">
                <h1>We Could Not Send Your Message</h1>
                <p>The website could not confirm email delivery. Your information may not have reached us.</p>
                <p>Please call or text <a href="tel:+15703525501">570-352-5501</a>, or try the form again in a few minutes.</p>
                <p><a class="vc_btn3" href="/contact-us/">Return to Contact Us</a></p>
            </div>
        </article>
    </div>
</main>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php'; ?>
