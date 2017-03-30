<?php

/* This is a simple example how to get multiple companies from Manta where the brand has access to */
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
foreach ( $oCompanies as $oCompany) {
    echo "<hr/><br/>";
    echo 'Company: ' . $oCompany->company . '<br/>';
    var_dump($oCompany);

}
echo PHP_EOL;