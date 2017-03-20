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

echo "Retrieving all companies of brand:", PHP_EOL;
$oCompanies = $session->getCompanies();
foreach ( $oCompanies as $oCompanies) {
    echo "<hr/><br/>";
    echo 'Company: ' . $oCompanies->company . '<br/>';
    var_dump($oCompanies);

}
echo PHP_EOL, PHP_EOL;

