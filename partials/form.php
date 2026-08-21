<?php

function pd_post_get(array $post, string $name): string
{
    if (isset($post[$name]) && is_string($post[$name])) {
        return trim($post[$name]);
    }
    $alt = str_replace('.', '_', $name);
    if ($alt !== $name && isset($post[$alt]) && is_string($post[$alt])) {
        return trim($post[$alt]);
    }
    return '';
}

function pd_validate_form(array $post): array
{
    $kind = pd_post_get($post, 'pd_form') ?: 'contact';
    if (pd_post_get($post, 'website') !== '') {
        return ['status' => 'honeypot', 'errors' => [], 'subject' => '', 'body' => ''];
    }
    $gravityHoneypot = match ($kind) {
        'moving' => 'input_14',
        'credit' => 'input_6',
        default => 'input_4',
    };
    if (pd_post_get($post, $gravityHoneypot) !== '') {
        return ['status' => 'honeypot', 'errors' => [], 'subject' => '', 'body' => ''];
    }

    if ($kind === 'moving') {
        $first = pd_post_get($post, 'input_4.3');
        $last = pd_post_get($post, 'input_4.6');
        $email = pd_post_get($post, 'input_10');
        $phone = pd_post_get($post, 'input_9');
        $message = pd_post_get($post, 'input_12');
        $subject = 'Piano Depot moving request';
    } else {
        $first = pd_post_get($post, 'input_1.3');
        $last = pd_post_get($post, 'input_1.6');
        $email = pd_post_get($post, 'input_2');
        $phone = pd_post_get($post, 'input_9');
        $message = pd_post_get($post, 'input_3');
        $subject = $kind === 'credit' ? 'Piano Depot Credit inquiry' : 'Piano Depot Contact';
    }

    $name = trim($first . ' ' . $last);
    $errors = [];
    if ($name === '') {
        $errors[] = 'name';
    }
    if ($kind === 'moving') {
        if ($phone === '' && $email === '') {
            $errors[] = 'phone or email';
        }
        if ($message === '') {
            $errors[] = 'message';
        }
    } else {
        if ($email === '') {
            $errors[] = 'email';
        }
        if ($message === '') {
            $errors[] = 'message';
        }
    }

    if ($errors !== []) {
        return ['status' => 'error', 'errors' => $errors, 'subject' => $subject, 'body' => ''];
    }

    $lines = [
        'Form: ' . $kind,
        'Name: ' . $name,
        'Email: ' . $email,
        'Phone: ' . $phone,
        'Message:',
        $message,
    ];
    foreach ($post as $key => $value) {
        if (in_array($key, ['website', 'pd_form', 'pd_redirect', 'gform_submit', 'is_submit_2', 'is_submit_1', 'is_submit_6', 'state_2', 'state_1', 'state_6', 'gform_unique_id', 'gform_target_page_number_2', 'gform_source_page_number_2', 'gform_field_values'], true)) {
            continue;
        }
        if (!is_string($value) || $value === '') {
            continue;
        }
        if (in_array($key, ['input_1.3', 'input_1.6', 'input_2', 'input_3', 'input_4.3', 'input_4.6', 'input_9', 'input_10', 'input_12'], true)) {
            continue;
        }
        $lines[] = $key . ': ' . $value;
    }

    return [
        'status' => 'ok',
        'errors' => [],
        'subject' => $subject,
        'body' => implode("\n", $lines),
    ];
}

function pd_form_banner_html(): string
{
    $sent = ($_GET['sent'] ?? '') === '1';
    $error = ($_GET['error'] ?? '') === '1';
    $mail = ($_GET['mail'] ?? '') === '0';
    if ($sent) {
        return '<p class="pd-form-banner pd-form-banner-ok">We got it — we will call you.</p>';
    }
    if ($error) {
        return '<p class="pd-form-banner pd-form-banner-error">Please fill this in: name, email, and message.</p>';
    }
    if ($mail) {
        return '<p class="pd-form-banner pd-form-banner-error">could not send — please call or text <a href="tel:+15703525501">570-352-5501</a></p>';
    }
    return '';
}

function pd_wire_form(string $html, string $path): string
{
    $kind = 'contact';
    if (str_contains($path, 'piano-moving')) {
        $kind = 'moving';
    } elseif (str_contains($path, 'apply-for-credit')) {
        $kind = 'credit';
    }
    $redirect = $path === '/' ? '/' : rtrim($path, '/') . '/';

    $html = preg_replace(
        '#(<form\b[^>]*\bid=[\'"]gform_[0-9]+[\'"][^>]*\baction=[\'"])[^\'"]*([\'"])#i',
        '$1/forms/send.php$2',
        $html
    ) ?? $html;

    $hidden = '<input type="hidden" name="pd_form" value="' . htmlspecialchars($kind, ENT_QUOTES) . '">'
        . '<input type="hidden" name="pd_redirect" value="' . htmlspecialchars($redirect, ENT_QUOTES) . '">'
        . '<input type="text" name="website" value="" class="pd-hp" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px">';

    $html = preg_replace(
        '#(<form\b[^>]*\bid=[\'"]gform_[0-9]+[\'"][^>]*>)#i',
        '$1' . $hidden,
        $html,
        1
    ) ?? $html;

    $html = '<style>.gform_validation_container{display:none!important}</style>' . $html;

    return $html;
}
