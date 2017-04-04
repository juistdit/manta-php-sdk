<?php

/* This is a simple example how to get one company from Manta */
require_once __DIR__ . '/../dist/manta-sdk-php.phar';

use Manta\Sdk;

$config = (include __DIR__ . '/config.php');

$company_id = 38;/* This is the internal Manta id, so should have been received by Manta first */

$sdk = new Sdk($config);

echo "Logging in...", PHP_EOL;
$session = $sdk->login($config['username'], $config['password']);
echo "Logged in.", PHP_EOL;
echo PHP_EOL;

echo "Retrieving company id: " . $company_id , PHP_EOL;
var_dump($session->getCompany($company_id));
echo PHP_EOL;
