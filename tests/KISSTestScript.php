<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 15-02-17
 * Time: 12:10
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Manta\Sdk;

$config = (include __DIR__ . '/config.php');

if(php_sapi_name() !== "cli") {
    echo "<html>";
    echo "<body>";
    echo "<pre>";
}
$sdk = new Sdk($config);

echo "Logging in with wrong credentials.", PHP_EOL;
try {
    $sdk->login("Username", "doesn't exist");
} catch (\Manta\Exceptions\AuthorizationException $e){
    echo $e;
}
echo PHP_EOL;

echo "Logging in...", PHP_EOL;
$session = $sdk->login($config['username'], $config['password']);
echo "Logged in.", PHP_EOL;
echo PHP_EOL;

echo "Retrieving company id = 33:", PHP_EOL;
var_dump($session->getCompany(33));
echo PHP_EOL, PHP_EOL;

echo "Retrieving company that does not exist.", PHP_EOL;
try {
    var_dump($session->getCompany(1024 * 1024));
} catch (\Manta\Exceptions\NoAccessException $e){
    echo $e;
}
echo PHP_EOL, PHP_EOL;

echo "Retrieving company without any access.", PHP_EOL;
try {
    var_dump($session->getCompany(20));
} catch(\Manta\Exceptions\NoAccessException $e) {
    echo $e;
}
echo PHP_EOL, PHP_EOL;

echo "Retrieving all companies:", PHP_EOL;
$companies = $session->getCompanies();

foreach($companies as $company) {
    echo " - " , $company->company;
    echo PHP_EOL;
}

if(php_sapi_name() !== "cli") {
    echo "</pre>";
    echo "</body>";
    echo "</html>";
}