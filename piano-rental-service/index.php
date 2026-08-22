<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/config.php';
$page = [
	'title' => 'Piano Rental Service | Piano Depot in Olyphant, PA',
	'description' => 'Learn about Piano Depot rental programs with lessons, an in-tune piano, and an available tuning maintenance agreement.',
	'extra_css' => [
		'/wp-content/uploads/elementor/css/post-349.css',
		'/wp-content/uploads/elementor/css/post-11.css',
		'/wp-content/uploads/elementor/css/post-14.css',
		'/wp-content/uploads/section-landing-pages.css',
	],
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/header.php';
$landing = [
	'eyebrow' => 'A Practical Start for Piano Students',
	'title' => 'Piano Rental Service',
	'intro' => 'Piano Depot offers rental programs for people who want to learn, enjoy music, or help their children learn music.',
	'lead_title' => 'A Supported Way to Begin',
	'lead' => 'We work to find practical instrument solutions for a range of budgets. Contact us to discuss current rental program details and the needs of the student.',
	'cards' => [
		['label' => 'Learning Support', 'title' => 'Lessons Included', 'description' => 'Our rental programs include lessons to help students begin the learning process.', 'href' => '/contact-us/', 'action' => 'Ask about rental lessons'],
		['label' => 'Instrument Care', 'title' => 'An In-Tune Piano', 'description' => 'Our rental programs include an in-tune piano so the student can learn and enjoy music on a properly maintained instrument.', 'href' => '/contact-us/', 'action' => 'Discuss a rental piano'],
		['label' => 'Maintenance Option', 'title' => 'Monthly Payment Package', 'description' => 'A tuning maintenance agreement can also be provided as part of one monthly payment package.', 'href' => '/contact-us/', 'action' => 'Request current details'],
	],
	'cta_title' => 'Ask us about piano rentals.',
	'cta_text' => 'Tell us who will be learning and what kind of instrument you are considering so we can discuss the current program with you.',
	'cta_href' => '/contact-us/',
	'cta_action' => 'Contact Piano Depot',
];
require $_SERVER['DOCUMENT_ROOT'] . '/partials/section-landing.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/partials/footer.php';
