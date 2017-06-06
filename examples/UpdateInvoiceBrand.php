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

$brandInvoiceId = 'brand_invoice_id_848';

$settingsScenario = [];

$settingsScenario['api'] = 'brand/invoices/brand/' . $brandInvoiceId;

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
$data = $requestBody = file_get_contents(__DIR__ . '/../tests/update_invoice.json');
$url=$basicUrl . $settingsScenario['api'];

$start = microtime(true);

$response = Request::put($url)
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