<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/partials/form.php';

$ok = pd_validate_form([
    'input_1.3' => 'Ada',
    'input_1.6' => 'Lovelace',
    'input_2' => 'ada@example.com',
    'input_3' => 'Need a piano',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($ok['status'] === 'ok', 'valid contact');
expect(str_contains($ok['body'], 'Ada'), 'body has name');
expect(str_contains($ok['subject'], 'Contact'), 'subject');

$bad = pd_validate_form([
    'input_1.3' => '',
    'input_1.6' => '',
    'input_2' => '',
    'input_3' => '',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($bad['status'] === 'error', 'empty is error');
expect(!empty($bad['errors']), 'errors listed');

$hp = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'website' => 'http://spam.example',
    'pd_form' => 'contact',
]);
expect($hp['status'] === 'honeypot', 'website honeypot');

$hp2 = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'input_4' => 'filled',
    'website' => '',
    'pd_form' => 'contact',
]);
expect($hp2['status'] === 'honeypot', 'input_4 honeypot');

$interest = pd_validate_form([
    'input_1.3' => 'Grace',
    'input_1.6' => 'Hopper',
    'input_2' => 'grace@example.com',
    'input_3' => 'Please tell me more',
    'input_4' => 'Learn More About This Product',
    'input_6' => '',
    'website' => '',
    'pd_form' => 'interest',
]);
expect($interest['status'] === 'ok', 'interest selection is not mistaken for spam');
expect(str_contains($interest['subject'], 'website inquiry'), 'interest subject');

$cfg = pd_config();
$emailPayload = pd_postmark_payload($cfg, $ok);
expect($emailPayload['To'] === 'frankbissol@gmail.com', 'Postmark recipient');
expect(str_contains($emailPayload['From'], 'ryan@congregationhub.com'), 'Postmark sender');
expect($emailPayload['ReplyTo'] === 'ada@example.com', 'Postmark reply-to visitor');

$sms = pd_sms_text($ok);
expect(str_contains($sms, 'Ada Lovelace'), 'text includes customer name');
expect(str_contains($sms, 'ada@example.com'), 'text includes customer contact');
