<?php

require_once dirname(__DIR__) . '/partials/config.php';
require_once dirname(__DIR__) . '/partials/form.php';

$now = 2000000000;
$started = (string) ($now - 30);

$ok = pd_validate_form([
    'input_1.3' => 'Ada',
    'input_1.6' => 'Lovelace',
    'input_2' => 'ada@example.com',
    'input_9' => '570-555-0100',
    'input_3' => 'Need a piano',
    'website' => '',
    'pd_started_at' => $started,
    'pd_form' => 'contact',
], $now);
expect($ok['status'] === 'ok', 'valid contact');
expect(str_contains($ok['body'], 'Ada'), 'body has name');
expect(str_contains($ok['body'], '570-555-0100'), 'body has optional phone');
expect(str_contains($ok['subject'], 'Contact'), 'subject');

$bad = pd_validate_form([
    'input_1.3' => '',
    'input_1.6' => '',
    'input_2' => '',
    'input_3' => '',
    'website' => '',
    'pd_started_at' => $started,
    'pd_form' => 'contact',
], $now);
expect($bad['status'] === 'error', 'empty is error');
expect(!empty($bad['errors']), 'errors listed');

$hp = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'website' => 'http://spam.example',
    'pd_started_at' => $started,
    'pd_form' => 'contact',
], $now);
expect($hp['status'] === 'honeypot', 'website honeypot');

$hp2 = pd_validate_form([
    'input_1.3' => 'Bot',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'input_4' => 'filled',
    'website' => '',
    'pd_started_at' => $started,
    'pd_form' => 'contact',
], $now);
expect($hp2['status'] === 'honeypot', 'input_4 honeypot');

$interest = pd_validate_form([
    'input_1.3' => 'Grace',
    'input_1.6' => 'Hopper',
    'input_2' => 'grace@example.com',
    'input_3' => 'Please tell me more',
    'input_4' => 'Learn More About This Product',
    'input_6' => '',
    'website' => '',
    'pd_started_at' => $started,
    'pd_form' => 'interest',
], $now);
expect($interest['status'] === 'ok', 'interest selection is not mistaken for spam');
expect(str_contains($interest['subject'], 'website inquiry'), 'interest subject');

$wired = pd_wire_form('<form id="gform_1" action="/old/"></form>', '/contact-us/');
expect(str_contains($wired, 'action="/forms/send.php"'), 'converted form uses local handler');
expect(str_contains($wired, 'name="pd_started_at" value="<?php echo time(); ?>"'), 'converted form receives dynamic start time');

$tooFast = pd_validate_form([
    'input_1.3' => 'Quick',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'website' => '',
    'pd_started_at' => (string) ($now - 1),
    'pd_form' => 'contact',
], $now);
expect($tooFast['status'] === 'honeypot', 'submission that is too fast is rejected');

$missingStartedAt = pd_validate_form([
    'input_1.3' => 'Direct',
    'input_1.6' => 'Bot',
    'input_2' => 'bot@example.com',
    'input_3' => 'spam',
    'website' => '',
    'pd_form' => 'contact',
], $now);
expect($missingStartedAt['status'] === 'honeypot', 'direct submission without form timestamp is rejected');

$invalidEmail = pd_validate_form([
    'input_1.3' => 'Invalid',
    'input_1.6' => 'Address',
    'input_2' => 'not-an-email',
    'input_3' => 'Please contact me',
    'website' => '',
    'pd_started_at' => $started,
    'pd_form' => 'contact',
], $now);
expect($invalidEmail['status'] === 'error', 'invalid email is rejected');

$rateDirectory = sys_get_temp_dir() . '/pianodepot-form-rate-test-' . bin2hex(random_bytes(6));
expect(pd_rate_limit_allowed('127.0.0.1|contact', $rateDirectory, $now, 2, 60), 'first submission is allowed');
expect(pd_rate_limit_allowed('127.0.0.1|contact', $rateDirectory, $now + 1, 2, 60), 'second submission is allowed');
expect(!pd_rate_limit_allowed('127.0.0.1|contact', $rateDirectory, $now + 2, 2, 60), 'submission over rate limit is rejected');
expect(pd_rate_limit_allowed('127.0.0.1|contact', $rateDirectory, $now + 61, 2, 60), 'rate limit expires');
foreach (glob($rateDirectory . '/*') ?: [] as $rateFile) {
    unlink($rateFile);
}
rmdir($rateDirectory);

foreach (['contact-us/index.php', 'piano-moving-form/index.php'] as $activeFormPage) {
    $activeFormHtml = file_get_contents(dirname(__DIR__) . '/' . $activeFormPage);
    expect(is_string($activeFormHtml) && str_contains($activeFormHtml, 'name="pd_started_at" value="<?php echo time(); ?>"'), $activeFormPage . ' has dynamic start time');
}

$cfg = pd_config();
$emailPayload = pd_postmark_payload($cfg, $ok);
expect($emailPayload['To'] === 'frankbissol@gmail.com', 'Postmark recipient');
expect(str_contains($emailPayload['From'], 'ryan@congregationhub.com'), 'Postmark sender');
expect($emailPayload['ReplyTo'] === 'ada@example.com', 'Postmark reply-to visitor');

$movingPayload = pd_postmark_payload($cfg, array_merge($ok, ['kind' => 'moving']));
expect(str_contains($movingPayload['To'], 'frankbissol@gmail.com'), 'moving email includes Frank');
expect(str_contains($movingPayload['To'], 'joenshar02@icloud.com'), 'moving email includes Joe');

$movingPhones = pd_telnyx_recipients($cfg, array_merge($ok, ['kind' => 'moving']));
expect(in_array('+15703525501', $movingPhones, true), 'moving text includes Frank');
expect(in_array('+15707662790', $movingPhones, true), 'moving text includes Joe');
$contactPhones = pd_telnyx_recipients($cfg, $ok);
expect($contactPhones === ['+15703525501'], 'other forms text only Frank');

$sms = pd_sms_text($ok);
expect(str_contains($sms, 'Name: Ada Lovelace'), 'text labels customer name');
expect(str_contains($sms, 'Phone: 570-555-0100'), 'text labels customer phone');
expect(str_contains($sms, 'Message: Need a piano'), 'text labels customer message');
expect(str_contains($sms, 'Email: ada@example.com'), 'text labels customer email');
