<?php
http_response_code(404);
$cfg = function_exists('pd_config') ? pd_config() : ['site_name' => 'Piano Depot'];
$page = [
    'title' => 'Page not found | Piano Depot',
    'description' => 'That page is not on Piano Depot.',
    'extra_css' => [],
];
if (defined('PD_ROOT') && is_file(PD_ROOT . '/partials/header.php')) {
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
if (defined('PD_ROOT') && is_file(PD_ROOT . '/partials/footer.php')) {
    require PD_ROOT . '/partials/footer.php';
} else {
    echo '</body></html>';
}
