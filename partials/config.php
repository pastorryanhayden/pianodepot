<?php

if (!defined('PD_ROOT')) {
    define('PD_ROOT', dirname(__DIR__));
}

ini_set('display_errors', '0');
error_reporting(E_ALL);

function pd_env(string $name, string $default = ''): string
{
    $value = getenv($name);
    if (is_string($value) && $value !== '') {
        return $value;
    }
    if (isset($_ENV[$name]) && is_string($_ENV[$name]) && $_ENV[$name] !== '') {
        return $_ENV[$name];
    }
    if (isset($_SERVER[$name]) && is_string($_SERVER[$name]) && $_SERVER[$name] !== '') {
        return $_SERVER[$name];
    }

    static $fileValues = null;
    if ($fileValues === null) {
        $envFile = PD_ROOT . '/.env';
        $parsed = is_file($envFile) ? @parse_ini_file($envFile, false, INI_SCANNER_RAW) : [];
        $fileValues = is_array($parsed) ? $parsed : [];
    }

    $fileValue = $fileValues[$name] ?? '';
    return is_string($fileValue) && $fileValue !== '' ? $fileValue : $default;
}

function pd_config(): array
{
    return [
        'site_name' => 'Piano Depot',
        'phone' => '570-352-5501',
        'phone_tel' => '+15703525501',
        'address' => '225 W. Lackawanna Ave., Olyphant, PA 18447',
        'email_to' => pd_env('MAIL_TO', 'frankbissol@gmail.com'),
        'email_from' => pd_env('MAIL_FROM', 'ryan@congregationhub.com'),
        'email_from_name' => pd_env('MAIL_FROM_NAME', 'Piano Depot Website'),
        'postmark_token' => pd_env('POSTMARK_SERVER_TOKEN'),
        'telnyx_api_key' => pd_env('TELNYX_API_KEY'),
        'telnyx_from' => pd_env('TELNYX_FROM'),
        'telnyx_messaging_profile_id' => pd_env('TELNYX_MESSAGING_PROFILE_ID'),
        'telnyx_to' => '+15703525501',
        'display_errors' => false,
    ];
}

$pd = pd_config();
ini_set('display_errors', $pd['display_errors'] ? '1' : '0');
