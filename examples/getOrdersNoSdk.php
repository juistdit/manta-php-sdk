<?php
/**
* Example how to get orders from Manta based on status
*
*/

include __DIR__ . "/../dist/httpful.phar";
use Httpful\Request;

$config = (include __DIR__ . '/config.php');

$basicUrl = $config['api_url'];

$settingsScenario = [];

$settingsScenario['api'] = 'brand/orders?status=new';
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
$data = [];
$url=$basicUrl . $settingsScenario['api'];
$response = Request::get($url)
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