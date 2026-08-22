<?php

require dirname(__DIR__) . '/partials/config.php';

$cfg = pd_config();

expect(defined('PD_ROOT'), 'PD_ROOT is defined');
expect(PD_ROOT === dirname(__DIR__), 'PD_ROOT is the project root');
expect($cfg['site_name'] === 'Piano Depot', 'site_name');
expect($cfg['phone'] === '570-352-5501', 'phone');
expect($cfg['phone_tel'] === '+15703525501', 'phone_tel');
expect($cfg['address'] === '225 W. Lackawanna Ave., Olyphant, PA 18447', 'address');
expect($cfg['email_to'] === 'frankbissol@gmail.com', 'form recipient');
expect($cfg['display_errors'] === false, 'display_errors is false');
