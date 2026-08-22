<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Yamaha Portable Digital Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Need a lightweight, space-saving piano? Piano Depot in Olyphant, PA offers the best selection of portable digital pianos perfect for home & stage. Visit us now!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/portable-digital-pianos/',
    'eyebrow' => 'Yamaha Portable Digital',
    'title' => 'Portable Digital Pianos',
    'intro' => 'Enjoy the touch and tone of a Yamaha acoustic piano wherever the music takes you. These portable, full-feature digital pianos let you turn any space into a studio, club stage, or concert hall.',
    'hero_image' => '/wp-content/uploads/2021/06/CpNzSo3U.jpeg',
    'models' => [
        ['name' => 'P-515', 'href' => '/product/p-515/', 'image' => '/wp-content/uploads/2021/07/P-515B-with-standpedal-300x300.jpg', 'description' => 'Flagship portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-125', 'href' => '/product/p-125/', 'image' => '/wp-content/uploads/2021/07/P-125B-with-standpedal-300x300.jpg', 'description' => 'Compact portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-121', 'href' => '/product/p-121/', 'image' => '/wp-content/uploads/2021/07/P-121BK-with-standpedal-300x300.jpg', 'description' => 'Shorter-key portable digital piano.', 'label' => 'Portable'],
        ['name' => 'P-45', 'href' => '/product/p-45/', 'image' => '/wp-content/uploads/2021/07/P-45-with-Stand-300x300.jpg', 'description' => 'Entry portable digital piano.', 'label' => 'Portable'],
        ['name' => 'DGX-670 Portable Grand Piano', 'href' => '/product/dgx-670-portable-grand-piano/', 'image' => '/wp-content/uploads/2021/07/DGX-670-Black-new-300x300.jpg', 'description' => 'Portable grand with accompaniment.', 'label' => 'Portable'],
    ],
    'videos' => [
        ['id' => 'WqSXOM49GZA', 'caption' => 'Yamaha P-515 Digital Piano Overview'],
    ],
    'cta_title' => 'Try a portable Yamaha in the showroom',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
