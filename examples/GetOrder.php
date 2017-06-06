<?php

/* This is a simple example how to get one order from Manta */
require_once __DIR__ . '/../dist/manta-sdk-php.phar';

use Manta\Sdk;

$id_order = 1033;  /* Internal <anta order id, which you should have received from Manta */

$config = (include __DIR__ . '/config.php');

$sdk = new Sdk($config);

echo "Logging in...", PHP_EOL;
$session = $sdk->login($config['username'], $config['password']);
echo "Logged in.", PHP_EOL;
echo PHP_EOL;


echo "Retrieving Order id = " . $id_order, PHP_EOL;
var_dump($session->getOrder($id_order));
echo PHP_EOL;


