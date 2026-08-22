<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Acoustic Upright Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Find Yamaha upright pianos at Piano Depot in Olyphant, PA. From beginner to professional models, we have the perfect upright piano for you! Visit our showroom!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/acoustic-upright-pianos/',
    'eyebrow' => 'Yamaha Acoustic Uprights',
    'title' => 'Acoustic Upright Pianos',
    'intro' => 'Elegant, compact pianos with a responsive keyboard and a clear, resonant tone — for beginners, budding virtuosos, and accomplished musicians.',
    'hero_image' => '/wp-content/uploads/2021/06/IgVWKrB0.jpeg',
    'models' => [
        ['name' => 'b Series', 'href' => '/product/b-series/', 'image' => '/wp-content/uploads/2021/11/bSeries_gallery-300x300.jpg', 'description' => 'Yamaha b Series uprights.', 'label' => 'Upright'],
        ['name' => 'P22 Piano', 'href' => '/product/p22-piano/', 'image' => '/wp-content/uploads/2021/11/pseries_gallery-300x300.webp', 'description' => 'Yamaha P22 studio upright.', 'label' => 'Upright'],
        ['name' => 'U Series', 'href' => '/product/u-series/', 'image' => '/wp-content/uploads/2021/11/USeries_Gallery-300x300.jpg', 'description' => 'Yamaha U Series uprights.', 'label' => 'Upright'],
        ['name' => 'YUS Series', 'href' => '/product/yus-series/', 'image' => '/wp-content/uploads/2021/11/YUS5-gallery-300x300.jpg', 'description' => 'Yamaha YUS Series uprights.', 'label' => 'Upright'],
    ],
    'videos' => [
        ['id' => 'fKGu54YKkSE', 'caption' => 'The Yamaha Story'],
    ],
    'cta_title' => 'Make an appointment to see an upright',
    'cta_text' => 'Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
