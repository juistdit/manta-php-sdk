<?php
/**
* Example how to update the stock of a single SKU
* SDK is not used in this example
*
*/

include __DIR__ . "/../dist/httpful.phar";
use Httpful\Request;

$config = (include __DIR__ . '/config.php');

$basicUrl = $config['api_url'];

$settingsScenario = [];

/* BEGIN ADJUST */
$settingsScenario['product_sku'] = 'SKLIGHTBOTTLE1'; // SKU
$settingsScenario['product_qty'] = 500;
$settingsScenario['is_in_stock'] = true;
/* END ADJUST */


$settingsScenario['api'] = 'brand/products/@@SKU@@/stockitems';
$settingsScenario['username'] = $config['username'];
$settingsScenario['password'] = $config['password'];
$settingsScenario['token_url'] = $basicUrl . 'integration/customer/token';


$data = array();
$data = array("username" => $settingsScenario['username'], "password" => $settingsScenario['password']);
$jsonRequest = json_encode($data);

/* START GET TOKEN */
$response = Request::post($settingsScenario['token_url'])
->body($jsonRequest)
->sendsJson()
->send();

if ( $response->code == 200 && !empty($response->raw_body) ) {
/* Token received successful */
$token = json_decode($response->raw_body);
}
else {
/* Something went wrong getting the token */
echo print_r($response);die();
}

unset($data);

$aHeaders = array('Authorization' => 'Bearer ' . $token );
$data = ['stockItem' => ['qty' => $settingsScenario['product_qty'], 'is_in_stock' => $settingsScenario['is_in_stock']]];
$url=$basicUrl . str_replace("@@SKU@@", $settingsScenario['product_sku'], $settingsScenario['api']);
$response = Request::put($url)
->body($data)
->addHeaders($aHeaders)
->sendsJson()
->send();

echo "Status: " . $response->code . "<br/>";
echo "url: " . $url. "<br/>";
echo "<pre>";
$data = json_decode($response->raw_body);
if($data === null){
    echo $response->raw_body;
} else {
    echo json_encode($data, JSON_PRETTY_PRINT);
}
echo "</pre>";