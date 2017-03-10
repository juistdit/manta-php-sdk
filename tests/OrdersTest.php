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

    public function testGetOrder(){
        $session = $this->_session;

        try {
            $order = $session->getOrder($this->_config['order_with_access']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);
            $this->assertEquals($order->company, "SDK Test company");
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
            $this->fail("Expected atleast one order to be returned for the current brand.");
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
        //Sprint 8:
        //foreach(['new', 'invoice', 'shipped', 'complete', 'canceled'] as $status) {
        //Sprint 7:
        foreach(['new', 'invoiced', 'shipped', 'complete', 'canceled'] as $status) {
            $this->assertContains($status, $allowed_statuses);
        }
    }

    public function testGetOrderStatusEqualTo() {
        $session = $this->_session;
        $allowed_statuses = $session->getOrders()->getAllowedStatuses();
        foreach($allowed_statuses as $status) {
            $orders = $session->getOrders()->statusEqualTo($status);
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
                $this->fail("Expected atleast one order to be returned for the current brand.");
            }
        }
    }

    public function testGetOrderStatusNot() {

        $session = $this->_session;
        $allowed_statuses = $session->getOrders()->getAllowedStatuses();
        foreach($allowed_statuses as $status) {
            $orders = $session->getOrders()->statusNot($status);
            $count = 0;
            foreach ($orders as $order) {
                $count++;
                $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);
                $this->assertNotEquals($status, $order->order_details['status']);
                try {
                    $order_2 = $session->getOrder($order->order_details['entity_id']);
                    $this->assertEquals($order, $order_2);
                } catch (\Manta\Exceptions\NoAccessException $e) {
                    $this->fail(sprintf("Failed requesting a singular order (%s), produced by the list in brands/orders.",
                        $order->details->entity_id));
                }
            }
            if ($count === 0) {
                $this->fail("Expected atleast one order to be returned for the current brand.");
            }
        }
        //$this->assertTrue(true);
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
                    $this->fail("Expected atleast one order to be returned for the current brand.");
                }
            }
        }
        //$this->assertTrue(true);
    }

}