<?php
/**
* Example how to update the stock of a single SKU
* SDK is not used in this example
*
*/

//include __DIR__ . "/../dist/httpful.phar";
//use Httpful\Request;

file_put_contents('/tmp/test-api-push-new-order.txt' . microtime(true) . '.json', 'api is run'  );
file_put_contents('/tmp/newOrderContent-' . microtime(true) . '.json', var_export($_REQUEST, true) );

$dataArray['process']['process_status'] = 'SUCCESS';
$dataArray['process']['process_message'] = 'Hello World';
$data = json_encode($dataArray);
header('Content-Type: application/json');
echo $data;

die();

$config = (include __DIR__ . '/config.php');

$basicUrl = $config['api_url'];

$settingsScenario = [];

$settingsScenario['api'] = 'brand/invoices/';
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
$data = $requestBody = file_get_contents(__DIR__ . '/../tests/create_invoice_one.json');
$url=$basicUrl . $settingsScenario['api'];

$start = microtime(true);

$response = Request::post($url)
->body($data)
->addHeaders($aHeaders)
->sendsJson()
->send();


$end = microtime(true);
$total = $end - $start;

echo 'Execution time:' . $total . "<br/>";
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