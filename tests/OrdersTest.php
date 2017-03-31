<?php
namespace Manta\Tests;

use Manta\DataObjects\Objects\Order;
use Manta\DataObjects\QuerySets\OrderQuerySet;
use PHPUnit\Framework\TestCase;

use Manta\Sdk as MantaSdk;
use Manta\Session;

/**
 * Created by PhpStorm.
 * User: kay
 * Date: 17-02-17
 * Time: 09:02
 */
class OrdersTest extends TestCase
{

    private $_config;
    private $_session;

    public function setUp() {
        $this->_config = (include __DIR__ . '/config.php');
        $sdk = new MantaSdk($this->_config);
        $this->_session = $sdk->login($this->_config['username'], $this->_config['password']);
    }

    public function testGetNonExistantOrder()
    {
        $session = $this->_session;

        try {
            $session->getOrder("Non-existantOrder");
            $this->fail("Retrieving a non-existant company should not work.");
        } catch (\Manta\Exceptions\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }

        try {
            $session->getOrder("-1");
            $this->fail("Retrieving a non-existant company should not work.");
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetNotAccessibleOrder() {
        $session = $this->_session;

        try {
            $session->getOrder($this->_config['order_without_access']);
            $this->fail("Retrieving a company that is not linked to the current user should not work.");
        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->assertTrue(true);
        }
    }

    public function testGetOrderDetails(){
        $session = $this->_session;

        try {
            $order = $session->getOrder($this->_config['order_with_access']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);

            file_put_contents('/tmp/order', var_export($order, true));
            $message = '';
            $label = 'Company';
            $expected =  $this->_config['order_accessible_company_name'];
            $actual = (isset($order->company)) ? $order->company : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual === $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Carriage';
            $expected =  $this->_config['order_accessible_carriage'];
            $actual = (isset($order->carriage)) ? $order->carriage : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual === $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Buyer Contact ID';
            $expected =  $this->_config['order_accessible_buyer_contact_id'];
            $actual = (isset($order->buyer_contact_id)) ? $order->buyer_contact_id : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual == $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Invoice Contact ID';
            $expected =  $this->_config['order_accessible_invoice_contact_id'];
            $actual = (isset($order->invoice_contact_id)) ? $order->invoice_contact_id : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual == $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Delivery Contact ID';
            $expected =  $this->_config['order_accessible_delivery_contact_id'];
            $actual = (isset($order->delivery_contact_id)) ? $order->delivery_contact_id : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual == $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Invoice Address ID';
            $expected =  $this->_config['order_accessible_invoice_address_id'];
            $actual = (isset($order->invoice_address_id)) ? $order->invoice_address_id : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual == $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Delivery Address ID';
            $expected =  $this->_config['order_accessible_delivery_address_id'];
            $actual = (isset($order->delivery_address_id)) ? $order->delivery_address_id : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual == $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            $label = 'Preferred Delivery Date';
            $expected =  $this->_config['order_accessible_preferred_delivery_date'];
            $actual = (isset($order->preferred_delivery_date)) ? $order->preferred_delivery_date : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual === $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            //TBD
            $label = 'Invoice Address';
            $expected =  $this->_config['order_accessible_invoice_address'];
            $actual = (isset($order->order_details['billing_address']['street'][0])) ? $order->order_details['billing_address']['street'][0] : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual === $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            //TBD
            $label = 'Delivery Address';
            $expected =  $this->_config['order_accessible_delivery_address'];

            $actual = (isset($order->order_details['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0])) ? $order->order_details['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0] : 'NOT_PRESENT' ;
            $message .= ( isset($actual) && ( $actual === $expected) ) ? '' : PHP_EOL . $label . '=> Expected:  ' . $expected . ', Actual: ' . $actual;

            if ( !empty($message)) {
                $this->fail($message);
            }


        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->fail(sprintf("Failed requesting order %s with username %s on api %s.",
                $this->_config['order_with_access'], $this->_config['username'], $this->_config['api_url']));
        }
    }

    public function testGetOrdersBasic() {
        $session = $this->_session;
        $orders = $session->getOrders();
        $count = 0;

        foreach($orders as $order){
            $count++;
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);
            $this->assertInternalType('string', $order->sales_person);
            try {
                $order_2 = $session->getOrder($order->order_details['entity_id']);
                $this->assertEquals($order, $order_2);
            } catch (\Manta\Exceptions\NoAccessException $e){
                $this->fail(sprintf("Failed requesting a singular order (%s), produced by the list in brands/orders.",
                    $order->details->entity_id));
            }
        }
        if($count === 0){
            $this->fail("Expected at least one order to be returned for the current brand.");
        }
    }



    public function testGetOrderDateBefore() {
        $this->assertTrue(true);
    }

    public function testGetOrderDateAfter(){
        $this->assertTrue(true);
    }

    public function testAllowedStatuses() {
        $session = $this->_session;
        $allowed_statuses = $session->getOrders()->getAllowedStatuses();

        $failedStatusses = '';
        foreach($allowed_statuses as $key=>$status) {
            try {
                $orders = $session->getOrders()->statusIn([$status]);
                foreach($orders as $order) {
                    $this->assertTrue(true);
                    continue;
                }
            } catch (\Manta\Exceptions\ApiException $e) {
                $failedStatusses .= $status . ',' ;
            }
        }
        if ( !empty($failedStatusses)) {
            $this->fail('Retrieving orders with the following statusses failed: ' . $failedStatusses );
        }
    }

    public function testGetOrderStatusEqualTo() {
        $session = $this->_session;
        $allowed_statuses = $session->getOrders()->getAllowedStatuses();
        foreach($allowed_statuses as $status) {
            $orders = $session->getOrders()->statusIn($allowed_statuses);
            $count = 0;
            foreach ($orders as $order) {
                $count++;
                $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);
                $this->assertEquals($status, $order->order_details['status']);
                try {
                    $order_2 = $session->getOrder($order->order_details['entity_id']);
                    $this->assertEquals($order, $order_2);
                } catch (\Manta\Exceptions\NoAccessException $e) {
                    $this->fail(sprintf("Failed requesting a singular order (%s), produced by the list in brands/orders.",
                        $order->details->entity_id));
                }
            }
            if ($count === 0) {
                $this->fail("Expected at least one order to be returned for the current brand.");
            }
        }
    }

    public function testGetOrderStatusNot() {

        $session = $this->_session;

        $not_allowed_statuses = $session->getOrders()->getNotAllowedStatuses();

        foreach($not_allowed_statuses as $key=>$status) {
            try {
                $orders = $session->getOrders()->statusIn([$status]);
                foreach($orders as $order) {
                    $this->fail("No orders are allowed to be retrieved with the status: " . $status . ', order id: ' . $order->order_details['entity_id'] );
                }
            } catch (\Manta\Exceptions\ApiException $e) {
                $this->assertTrue(true);
                continue;
            }
        }
    }

    public function testGetOrderStatusIn() {
        $session = $this->_session;
        $allowed_statuses = $session->getOrders()->getAllowedStatuses();
        foreach($allowed_statuses as $status_1) {
            foreach ($allowed_statuses as $status_2) {
                if($status_1 === $status_2) {
                    continue;
                }
                $orders = $session->getOrders()->statusIn([$status_1, $status_2]);
                $count = 0;
                foreach ($orders as $order) {
                    $count++;
                    $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);
                    $this->assertContains($order->order_details['status'], [$status_1, $status_2]);
                    try {
                        $order_2 = $session->getOrder($order->order_details['entity_id']);
                        $this->assertEquals($order, $order_2);
                    } catch (\Manta\Exceptions\NoAccessException $e) {
                        $this->fail(sprintf("Failed requesting a singular order (%s), produced by the list in brands/orders.",
                            $order->details->entity_id));
                    }
                }
                if ($count === 0) {
                    $this->fail("Expected at least one order to be returned for the current brand.");
                }
            }
        }
        //$this->assertTrue(true);
    }

}