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

$blocked4 = pd_resolve('/.env', $root);
expect($blocked4['kind'] === 'not_found', '.env is not web-served');
