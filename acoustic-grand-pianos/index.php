<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Yamaha Acoustic Grand Pianos | Piano Depot in Olyphant, PA',
    'description' => 'Explore Yamaha acoustic grand pianos at Piano Depot in Olyphant, PA. Shop the best grand pianos with expert advice & financing available. Visit us today!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/acoustic-grand-pianos/',
    'eyebrow' => 'Yamaha Acoustic Grands',
    'title' => 'Acoustic Grand Pianos',
    'intro' => 'Each Yamaha acoustic grand is assembled by artisans in a tradition of old-world craftsmanship. These instruments are sold in store only — call or email to make an appointment.',
    'hero_image' => '/wp-content/uploads/2021/06/j2glbwww.jpeg',
    'models' => [
        ['name' => 'GB1K / GC Series: 5′ to 5′ 8″', 'href' => '/product/gb1k-gc-series-5-to-5-8/', 'image' => '/wp-content/uploads/2021/09/GB1K-1-PolishedEbony-piano-300x300.jpg', 'description' => 'Yamaha baby grand series.', 'label' => 'Grand'],
        ['name' => 'CX Series: 5′ 3″ to 7′ 6″', 'href' => '/product/cx-series-5-3-to-7-6/', 'image' => '/wp-content/uploads/2021/09/CX_Satin-Ebony-300x300.jpg', 'description' => 'Yamaha CX conservatory grands.', 'label' => 'Grand'],
        ['name' => 'SX Series: 6′ 1″ to 7′ 6″', 'href' => '/product/sx-series-6-1-to-7-6/', 'image' => '/wp-content/uploads/2021/09/SX-polishedebony-300x300.jpg', 'description' => 'Yamaha SX performance grands.', 'label' => 'Grand'],
        ['name' => 'CF Series: 6′ 3″ to 9′', 'href' => '/product/cf-series-6-3-to-9/', 'image' => '/wp-content/uploads/2021/09/CF-polishedebony-300x300.jpg', 'description' => 'Yamaha CF concert grands.', 'label' => 'Grand'],
    ],
    'videos' => [
        ['id' => 'tTg2D3rFdcw', 'caption' => 'Yamaha Grand Piano from Kakegawa Factory'],
    ],
    'cta_title' => 'Make an appointment to see a grand',
    'cta_text' => 'These fine instruments are sold in store only. Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
