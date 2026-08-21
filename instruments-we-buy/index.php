<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = ['title' => 'Instruments We Buy | Piano Depot', 'description' => 'Piano Depot buys selected pianos and organs. Learn what we consider and contact us about your instrument.', 'extra_css' => ['/wp-content/uploads/section-landing-pages.css']];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
$landing = [
	'eyebrow' => 'Sell Your Instrument', 'title' => 'Instruments We Buy',
	'intro' => 'If you have a piano or organ you no longer need, Piano Depot can help determine whether it is a good fit for our inventory and service area.',
	'lead_title' => 'Start With the Right Information', 'lead' => 'The instrument’s make, model, age, condition, location, access, and clear photographs help us evaluate it accurately. Choose the instrument type below to see what information to send.',
	'cards' => [
		['label' => 'Acoustic & Digital', 'title' => 'Pianos We Buy', 'description' => 'Learn which grand, upright, player, and digital pianos we may consider and how to submit yours for review.', 'href' => '/pianos-we-buy/', 'action' => 'Tell us about your piano'],
		['label' => 'Vintage & Modern', 'title' => 'Organs We Buy', 'description' => 'See the kinds of Hammond, Leslie, church, home, and electronic organs that may be of interest to Piano Depot.', 'href' => '/organs-we-buy/', 'action' => 'Tell us about your organ'],
	],
	'cta_title' => 'Ready to send the details?', 'cta_text' => 'Include the model, location, condition, and photographs whenever possible.', 'cta_href' => '/contact-us/', 'cta_action' => 'Contact Piano Depot',
];
require $_SERVER['DOCUMENT_ROOT'] . '/partials/section-landing.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
