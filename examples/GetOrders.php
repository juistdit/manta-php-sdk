<?php

/* This is a simple example how to get multiple orders from Manta as a brand based on status */
require_once __DIR__ . '/../dist/manta-sdk-php.phar';

use Manta\Sdk;

$config = (include __DIR__ . '/config.php');

$sdk = new Sdk($config);

echo "Logging in...", PHP_EOL;
$session = $sdk->login($config['username'], $config['password']);
echo "Logged in.", PHP_EOL;
echo PHP_EOL;

echo "Retrieving All orders", PHP_EOL;

/* Set the status */
$status_1='new';

/* Add more status, the SDK will combine them in one output */
//$status_2='complete';

$oOrders = $session->getOrders()->statusIn([$status_1]);

//$oOrders = $session->getOrders()->statusIn([$status_1, $status_2]);
foreach ($oOrders as $oOrder) {
    echo "<hr/><br/>";
    var_dump($oOrder);
}

echo PHP_EOL;
