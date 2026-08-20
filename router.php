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
