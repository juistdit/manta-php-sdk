<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 15-02-17
 * Time: 12:10
 */
require_once __DIR__ . '/../dist/manta-sdk-php.phar';

use Manta\Sdk;

$config = (include __DIR__ . '/config.php');

$sdk = new Sdk($config);

echo "Logging in...", PHP_EOL;
$session = $sdk->login($config['username'], $config['password']);
echo "Logged in.", PHP_EOL;
echo PHP_EOL;

echo "Retrieving company id = 33:", PHP_EOL;
var_dump($session->getCompany(45));
echo PHP_EOL, PHP_EOL;

