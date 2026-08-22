<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
    'title' => 'Silent & Trans Acoustic Pianos in Olyphant, PA | Piano Depot',
    'description' => 'Discover Yamaha Silent & TransAcoustic pianos at Piano Depot in Olyphant, PA. Check out cutting-edge technology for quiet or enhanced acoustic playing.',
    'extra_css' => ['/wp-content/uploads/piano-type-pages.css'],
];
$catalog = [
    'nav' => '/acoustic-silent-trans-acoustic-pianos/',
    'eyebrow' => 'Silent & TransAcoustic',
    'title' => 'Silent & TransAcoustic Pianos',
    'intro' => 'By day it is a world-renowned acoustic piano. By night, the neighbors will not hear a thing. SILENT Piano puts concert-grand sound in your headphones, and TransAcoustic uses the soundboard to amplify digital voices through the instrument itself.',
    'hero_image' => '/wp-content/uploads/2021/06/duoXPjwM.jpeg',
    'models' => [
        ['name' => 'TA2 TransAcoustic', 'href' => '/product/ta2-transacoustic/', 'image' => '/wp-content/uploads/2021/09/TA2-lineup-300x300.jpg', 'description' => 'TransAcoustic piano with digital voices through the soundboard.', 'label' => 'TransAcoustic'],
        ['name' => 'SC2 Silent Piano', 'href' => '/product/sc2-silent-piano/', 'image' => '/wp-content/uploads/2021/09/b2-SC2-pe-300x300.jpg', 'description' => 'Silent system on a Yamaha acoustic upright.', 'label' => 'Silent'],
        ['name' => 'SH2 Silent Piano', 'href' => '/product/sh2-silent-piano/', 'image' => '/wp-content/uploads/2021/09/gc1PEC-SH2_Front-300x300.jpg', 'description' => 'Silent system on a Yamaha acoustic grand.', 'label' => 'Silent'],
    ],
    'videos' => [
        ['id' => 'Xu36GOKXs5M', 'caption' => 'Expand your acoustic piano with MIDI'],
    ],
    'cta_title' => 'Hear a Silent or TransAcoustic piano',
    'cta_text' => 'Call or text ahead for an in-store demonstration. Ask for Frank Bissol.',
    'cta_action' => 'Schedule an Appointment',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
require $_SERVER['DOCUMENT_ROOT'] . '/partials/piano-type-catalog.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
