<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Shop Used & Refurbished Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Looking for a quality used piano? Piano Depot in Olyphant, PA offers expertly refurbished grand, upright, and digital pianos at great prices. Visit us today!',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/used-and-refurbished/',
    'eyebrow' => 'Used & Refurbished',
    'title' => 'Used & Refurbished Pianos',
    'intro' => 'Our inventory is constantly changing and it is best to call for what is available. We keep an assortment of used and refurbished grands and uprights — spinets, consoles, and studio consoles — expertly refurbished, inspected, and finely tuned.',
    'hero_image' => '/wp-content/uploads/2021/06/XVVoBzWk.jpeg',
    'groups' => [
        [
            'id' => 'used-grands',
            'title' => 'Restored Grand Pianos',
            'href' => '/used-refurbished-grand-pianos/',
            'models' => [
                ['name' => 'Story & Clark (Player)', 'href' => '/product/story-clark/', 'image' => '/wp-content/uploads/2022/09/SrotyClark-Player-front-300x300.jpg', 'description' => 'Refurbished player grand.', 'label' => 'Used Grand'],
                ['name' => 'Baldwin Grand', 'href' => '/product/baldwin-grand/', 'image' => '/wp-content/uploads/2021/11/Baldwin-Grand-300x300.jpg', 'description' => 'Refurbished Baldwin grand.', 'label' => 'Used Grand'],
                ['name' => 'Yamaha GH1', 'href' => '/product/yamaha-gh1/', 'image' => '/wp-content/uploads/2021/11/IMG_0815-300x300.jpg', 'description' => 'Refurbished Yamaha GH1 grand.', 'label' => 'Used Grand'],
            ],
        ],
        [
            'id' => 'used-uprights',
            'title' => 'Restored Upright Pianos',
            'href' => '/used-refurbished-upright-pianos/',
            'models' => [
                ['name' => 'Knabe & Co', 'href' => '/product/knabe-co-player-piano/', 'image' => '/wp-content/uploads/2022/03/knabeco01.large_-300x300.jpg', 'description' => 'Refurbished Knabe upright.', 'label' => 'Used Upright'],
                ['name' => 'Yamaha U3', 'href' => '/product/yamaha-u3/', 'image' => '/wp-content/uploads/2022/03/yamaha-u3-01.large_-300x300.jpg', 'description' => 'Refurbished Yamaha U3.', 'label' => 'Used Upright'],
                ['name' => 'Young Chang F-110', 'href' => '/product/young-chang-f110/', 'image' => '/wp-content/uploads/2022/09/YoungChang-F110-farside-300x300.jpg', 'description' => 'Refurbished Young Chang F-110.', 'label' => 'Used Upright'],
                ['name' => 'Baldwin 243 SB', 'href' => '/product/baldwin-243-sb/', 'image' => '/wp-content/uploads/2021/11/Baldwin-243B.large_-300x300.jpg', 'description' => 'Refurbished Baldwin 243 SB.', 'label' => 'Used Upright'],
                ['name' => 'Kawai', 'href' => '/product/kawai-walnut/', 'image' => '/wp-content/uploads/2022/09/KawaiW-front-300x300.jpg', 'description' => 'Refurbished Kawai walnut upright.', 'label' => 'Used Upright'],
                ['name' => 'Shaw', 'href' => '/product/shaw/', 'image' => '/wp-content/uploads/2021/11/Shaw-Upright.jpeg', 'description' => 'Refurbished Shaw upright.', 'label' => 'Used Upright'],
                ['name' => 'Yamaha M214B W', 'href' => '/product/yamaha-m214b/', 'image' => '/wp-content/uploads/2021/11/Yamaha-M214B-Walnut.large_-1-300x300.jpg', 'description' => 'Refurbished Yamaha M214B.', 'label' => 'Used Upright'],
            ],
        ],
        [
            'id' => 'used-consoles',
            'title' => 'Restored Console Pianos',
            'href' => '/used-refurbished-console-pianos/',
            'models' => [
                ['name' => 'Kimball', 'href' => '/product/kimball-console/', 'image' => '/wp-content/uploads/2022/09/Kimball-BrMah-front-300x300.jpg', 'description' => 'Refurbished Kimball console.', 'label' => 'Used Console'],
                ['name' => 'Hyundai U810', 'href' => '/product/hyundai-u810/', 'image' => '/wp-content/uploads/2021/11/Hyundai.large_-300x300.jpg', 'description' => 'Refurbished Hyundai U810.', 'label' => 'Used Console'],
                ['name' => 'Kimball Artist Console', 'href' => '/product/kimball-artist-console/', 'image' => '/wp-content/uploads/2021/11/Kimball-Artist-Console-4244-GoldenOak.large_-300x300.jpg', 'description' => 'Refurbished Kimball artist console.', 'label' => 'Used Console'],
            ],
        ],
        [
            'id' => 'used-spinets',
            'title' => 'Restored Spinet Pianos',
            'href' => '/used-refurbished-spinet-pianos/',
            'models' => [
                ['name' => 'Baldwin Howard', 'href' => '/product/baldwin-howard/', 'image' => '/wp-content/uploads/2021/11/Baldwin-Howard-GoldenOak.large_-300x300.jpg', 'description' => 'Refurbished Baldwin Howard spinet.', 'label' => 'Used Spinet'],
                ['name' => 'Winter DarkOak Cabinet', 'href' => '/product/winter-piano/', 'image' => '/wp-content/uploads/2021/11/Winter-Spinet-BrMah.large_-300x300.jpg', 'description' => 'Refurbished Winter spinet.', 'label' => 'Used Spinet'],
            ],
        ],
        [
            'id' => 'used-digital',
            'title' => 'Restored Digital Pianos',
            'href' => '/used-refurbished-digital-pianos/',
            'models' => [],
        ],
    ],
    'gallery' => [
        ['src' => '/wp-content/uploads/2021/11/IMG_0372.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0371.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0365.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0375.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0318.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0317.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0302-1.jpg', 'alt' => 'Used pianos at Piano Depot'],
        ['src' => '/wp-content/uploads/2021/11/IMG_0301.jpg', 'alt' => 'Used pianos at Piano Depot'],
    ],
    'videos' => [
        ['id' => 'Cz3oCAp2Nts', 'caption' => 'Hallet Davis QRS Player Demo'],
    ],
    'cta_title' => 'Call for current used inventory',
    'cta_text' => 'Inventory changes. Please call or text ahead to see what is in the showroom and warehouse. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
