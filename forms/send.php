<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/form.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: /contact-us/', true, 302);
    exit;
}

$cfg = pd_config();
$result = pd_validate_form($_POST);
$back = '/contact-us/';
$ref = $_POST['pd_redirect'] ?? '';
if (!is_string($ref) || $ref === '') {
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
}
if (is_string($ref) && $ref !== '') {
    $path = parse_url($ref, PHP_URL_PATH);
    if (is_string($path) && str_starts_with($path, '/')) {
        $dir = PD_ROOT . rtrim($path, '/');
        if ($path === '/' || is_dir($dir) || is_file($dir . '/index.php')) {
            $back = $path;
        }
    }
}

$query = static function (string $back, string $qs): string {
    $sep = str_contains($back, '?') ? '&' : '?';
    return $back . $sep . $qs;
};

if ($result['status'] === 'honeypot') {
    header('Location: ' . $query($back, 'sent=1'), true, 302);
    exit;
}

if ($result['status'] === 'error') {
    header('Location: ' . $query($back, 'error=1'), true, 302);
    exit;
}

$sent = @mail(
    $cfg['email_to'],
    $result['subject'],
    $result['body'],
    'From: ' . $cfg['email_to'] . "\r\n" . 'Content-Type: text/plain; charset=UTF-8'
);

header('Location: ' . $query($back, $sent ? 'sent=1' : 'mail=0'), true, 302);
exit;
