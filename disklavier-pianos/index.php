<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Yamaha Disklavier Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Yamaha Disklavier pianos offer the best of acoustic sound and digital control. Visit Piano Depot in Olyphant, PA to find the perfect model for your needs.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/disklavier-pianos/',
    'eyebrow' => 'Yamaha Disklavier',
    'title' => 'Disklavier Pianos',
    'intro' => 'In 1982, it started with a simple idea: an acoustic piano with a record and playback system unlike any other. More than 30 years of innovation created a piano that can faithfully reproduce every nuance of a performance and stream it wirelessly — including into your living room.',
    'hero_image' => '/wp-content/uploads/2021/06/vA-6hReg.jpeg',
    'yamaha_href' => 'https://usa.yamaha.com/products/musical_instruments/pianos/disklavier/index.html',
    'yamaha_label' => 'View catalog on Yamaha’s website',
    'models' => [
        ['name' => 'Enspire CL', 'href' => '/product/enspire-cl/', 'image' => '/wp-content/uploads/2021/11/CL-lineup-300x300.jpg', 'description' => 'Disklavier Enspire CL player piano.', 'label' => 'Disklavier'],
        ['name' => 'Enspire ST', 'href' => '/product/enspire-st/', 'image' => '/wp-content/uploads/2021/11/ST-lineup-300x300.jpg', 'description' => 'Disklavier Enspire ST player piano.', 'label' => 'Disklavier'],
        ['name' => 'Enspire Pro', 'href' => '/product/enspire-pro/', 'image' => '/wp-content/uploads/2021/11/012-Disklavier-Enspire_Recording-Studio_6R9A5990-300x300.jpg', 'description' => 'Disklavier Enspire Pro for recording and performance.', 'label' => 'Disklavier'],
        ['name' => 'DKC-900 Upgrade Kit', 'href' => '/product/dkc-900-upgrade-kit/', 'image' => '/wp-content/uploads/2021/11/DKC-900.jpg', 'description' => 'Upgrade kit for compatible Disklavier pianos.', 'label' => 'Disklavier'],
    ],
    'videos' => [
        ['id' => 'T9Qv5B1Eb-k', 'caption' => 'Overview of the Yamaha Disklavier Enspire Player Piano App'],
        ['id' => 'U1cNpWSI9Nw', 'caption' => 'Yamaha Enspire Piano Is A Song Writers Dream Piano'],
        ['id' => 'Xu36GOKXs5M', 'caption' => 'Expand Your Acoustic Piano With MIDI'],
        ['id' => 'kvUFUKFUDC4', 'caption' => 'Enspire Pianos Worlds Best Piano For Recording Music'],
        ['id' => 'hlvBms8IW7o', 'caption' => 'Smart Key Helps Anyone To Play the Piano'],
    ],
    'cta_title' => 'Make an appointment to see a Disklavier',
    'cta_text' => 'These instruments are shown in store. Please call or text ahead. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
