<?php

if (!defined('PD_ROOT')) {
    define('PD_ROOT', dirname(__DIR__));
}

ini_set('display_errors', '0');
error_reporting(E_ALL);

function pd_config(): array
{
    return [
        'site_name' => 'Piano Depot',
        'phone' => '570-352-5501',
        'phone_tel' => '+15703525501',
        'address' => '225 W. Lackawanna Ave., Olyphant, PA 18447',
        'email_to' => 'frankbissol@gmail.com',
        'display_errors' => false,
    ];
}

$pd = pd_config();
ini_set('display_errors', $pd['display_errors'] ? '1' : '0');
