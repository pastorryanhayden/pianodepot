<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Workstation Keyboards in Olyphant, PA | Piano Depot',
    'description' => 'Looking for a powerful workstation keyboard? Piano Depot in Olyphant, PA offers top-rated professional keyboards with advanced features for musicians.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/workstation-keyboards/',
    'eyebrow' => 'Yamaha Arrangers',
    'title' => 'Workstation Keyboards',
    'intro' => 'Songwriting and performance keyboards with pro-level connectivity, real instrument Voices, Styles, effects, and a redesigned interface.',
    'hero_image' => '/wp-content/uploads/2021/06/wsm9Gy8I.jpeg',
    'models' => [
        ['name' => 'PSR-SX600', 'href' => '/product/psr-sx600/', 'image' => '/wp-content/uploads/2021/07/SX600_frontview-300x300.webp', 'description' => 'Yamaha PSR-SX600 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'PSR-SX700', 'href' => '/product/psr-sx700/', 'image' => '/wp-content/uploads/2021/07/SX700_front.png', 'description' => 'Yamaha PSR-SX700 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'PSR-SX900', 'href' => '/product/psr-sx900/', 'image' => '/wp-content/uploads/2021/07/SX900_front.jpg', 'description' => 'Yamaha PSR-SX900 arranger workstation.', 'label' => 'Workstation'],
        ['name' => 'Genos', 'href' => '/product/genos/', 'image' => '/wp-content/uploads/2021/09/Genos_Uptop-2-300x300.jpg', 'description' => 'Yamaha Genos flagship arranger.', 'label' => 'Workstation'],
    ],
    'videos' => [
        ['id' => '32AdjKpF8b4', 'caption' => 'Playing the Yamaha PSR-S970'],
    ],
    'cta_title' => 'See a workstation in the showroom',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
