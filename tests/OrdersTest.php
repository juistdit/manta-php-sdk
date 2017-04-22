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
        $message = '';
        try {
            $order = $session->getOrder($this->_config['order_with_access']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);

            file_put_contents('/tmp/order', var_export($order, true));

            $label = 'Company';
            $expected =  (isset($this->_config['order_accessible_company_name']) ) ? $this->_config['order_accessible_company_name'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->company)) ? $order->company : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Carriage';
            $expected =  (isset($this->_config['order_accessible_carriage']) ) ? $this->_config['order_accessible_carriage'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->carriage)) ? $order->carriage : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Buyer Contact ID';
            $expected =  (isset($this->_config['order_accessible_buyer_contact_id']) ) ? $this->_config['order_accessible_buyer_contact_id'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->buyer_contact_id)) ? $order->buyer_contact_id : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual == $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Invoice Contact ID';
            $expected =  (isset($this->_config['order_accessible_invoice_contact_id']) ) ? $this->_config['order_accessible_invoice_contact_id'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->invoice_contact_id)) ? $order->invoice_contact_id : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual == $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Delivery Contact ID';
            $expected =  (isset($this->_config['order_accessible_delivery_contact_id']) ) ? $this->_config['order_accessible_delivery_contact_id'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->delivery_contact_id)) ? $order->delivery_contact_id : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual == $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Invoice Address ID';
            $expected =  (isset($this->_config['order_accessible_invoice_address_id']) ) ? $this->_config['order_accessible_invoice_address_id'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                    $actual = (isset($order->invoice_address_id)) ? $order->invoice_address_id : 'NOT_PRESENT';
                    $message .= (isset($actual) && ($actual == $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

            $label = 'Delivery Address ID';
            $expected =  (isset($this->_config['order_accessible_delivery_address_id']) ) ? $this->_config['order_accessible_delivery_address_id'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->delivery_address_id)) ? $order->delivery_address_id : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual == $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

            $label = 'Preferred Delivery Date';
            $expected =  (isset($this->_config['order_accessible_preferred_delivery_date']) ) ? $this->_config['order_accessible_preferred_delivery_date'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->preferred_delivery_date)) ? $order->preferred_delivery_date : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Invoice Address';
            $expected =  (isset($this->_config['order_accessible_invoice_address']) ) ? $this->_config['order_accessible_invoice_address'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->order_details['billing_address']['street'][0])) ? $order->order_details['billing_address']['street'][0] : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Delivery Address';
            $expected =  (isset($this->_config['order_accessible_delivery_address']) ) ? $this->_config['order_accessible_delivery_address'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->order_details['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0])) ? $order->order_details['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0] : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Sales Person';
            $expected =  (isset($this->_config['order_accessible_sales_person']) ) ? $this->_config['order_accessible_sales_person'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->sales_person)) ? $order->sales_person : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Payment Terms';
            $expected =  (isset($this->_config['order_accessible_payment_terms']) ) ? $this->_config['order_accessible_payment_terms'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->payment_terms)) ? $order->payment_terms : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Brand Remark';
            $expected =  (isset($this->_config['order_accessible_brand_remark']) ) ? $this->_config['order_accessible_brand_remark'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->brand_remark)) ? $order->brand_remark : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Company_brand_code';
            $expected =  (isset($this->_config['order_accessible_company_brand_code']) ) ? $this->_config['order_accessible_company_brand_code'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->company_brand_code)) ? $order->company_brand_code : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Company Order Reference';
            $expected =  (isset($this->_config['order_accessible_company_order_reference']) ) ? $this->_config['order_accessible_company_order_reference'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->company_order_reference)) ? $order->company_order_reference : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Order id Brand';
            $expected =  (isset($this->_config['order_accessible_order_id_brand']) ) ? $this->_config['order_accessible_order_id_brand'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->order_id_brand)) ? $order->order_id_brand : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Brand invoice address code';
            $expected =  (isset($this->_config['order_accessible_brand_invoice_address_code']) ) ? $this->_config['order_accessible_brand_invoice_address_code'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->brand_invoice_address_code)) ? $order->brand_invoice_address_code : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Brand delivery address code';
            $expected =  (isset($this->_config['order_accessible_brand_delivery_address_code']) ) ? $this->_config['order_accessible_brand_delivery_address_code'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->brand_delivery_address_code)) ? $order->brand_delivery_address_code : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Order location';
            $expected =  (isset($this->_config['order_accessible_order_location']) ) ? $this->_config['order_accessible_order_location'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->order_location)) ? $order->order_location : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Payment method';
            $expected =  (isset($this->_config['order_accessible_payment_method']) ) ? $this->_config['order_accessible_payment_method'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->payment_method)) ? $order->payment_method : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $label = 'Brand discount';
            $expected =  (isset($this->_config['order_accessible_brand_discount']) ) ? $this->_config['order_accessible_brand_discount'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->brand_discount)) ? $order->brand_discount : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }

            $total_row_total_incl_tax = 0;
            $total_row_discount = 0;
            if ( isset($order->order_details['items'])) {
                $teller = 0;
                foreach ( $order->order_details['items'] as $aItem) {
                    $sku = $aItem['sku'];

                    $label = 'Item ' . $sku . ' - original_price';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['original_price']) ) ? $this->_config['order_accessible_items'][$sku]['original_price'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        $actual = (isset($aItem['original_price'])) ? $aItem['original_price'] : 'NOT_PRESENT';
                        $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    $label = 'Item ' . $sku . ' - discount_percent';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['discount_percent']) ) ? $this->_config['order_accessible_items'][$sku]['discount_percent'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        file_put_contents('/tmp/item', var_export($aItem, true));
                        $actual = (isset($aItem['discount_percent'])) ? $aItem['discount_percent'] : 'NOT_PRESENT';
                        $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    $label = 'Item ' . $sku . ' - qty_ordered';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['qty_ordered']) ) ? $this->_config['order_accessible_items'][$sku]['qty_ordered'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        $actual = (isset($aItem['qty_ordered'])) ? $aItem['qty_ordered'] : 'NOT_PRESENT';
                        $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    $label = 'Item ' . $sku . ' - tax_amount';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['tax_amount']) ) ? $this->_config['order_accessible_items'][$sku]['tax_amount'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        $actual = (isset($aItem['tax_amount'])) ? $aItem['tax_amount'] : 'NOT_PRESENT';
                        $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    $label = 'Item ' . $sku . ' - row_total';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['row_total']) ) ? $this->_config['order_accessible_items'][$sku]['row_total'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        $actual = (isset($aItem['row_total'])) ? $aItem['row_total'] : 'NOT_PRESENT';
                        $message .= (isset($actual) && (round($actual,2) === round($expected,2))) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    $label = 'Item ' . $sku . ' - row_total_incl_tax - minius discount';
                    $expected =  (isset($this->_config['order_accessible_items'][$sku]['row_total_incl_tax_minus_discount']) ) ? $this->_config['order_accessible_items'][$sku]['row_total_incl_tax_minus_discount'] : 'DONOTCHECK';
                    if ( $expected != 'DONOTCHECK' ) {
                        $actual = (isset($aItem['row_total_incl_tax']) && isset($aItem['discount_amount']) ) ? $aItem['row_total_incl_tax'] - $aItem['discount_amount']  : 'NOT_PRESENT';
                        $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                    }

                    /* Calculate the totals and check them later */
                    $total_row_total_incl_tax = $total_row_total_incl_tax + $aItem['row_total_incl_tax'];
                    $total_row_discount = $total_row_discount + $aItem['discount_amount'];
                }

                $label = 'Total excl Carriage';
                $expected =  (isset($this->_config['order_accessible_grand_total_minus_carriage']) ) ? $this->_config['order_accessible_grand_total_minus_carriage'] : 'DONOTCHECK';
                if ( $expected != 'DONOTCHECK' ) {
                    $actual = (isset($order->order_details['grand_total'])) ? $order->order_details['grand_total'] - $order->carriage: 'NOT_PRESENT';
                    $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                }

                $label = 'Total incl Carriage';
                $expected =  (isset($this->_config['order_accessible_grand_total']) ) ? $this->_config['order_accessible_grand_total'] : 'DONOTCHECK';
                if ( $expected != 'DONOTCHECK' ) {
                    $actual = (isset($order->order_details['grand_total'])) ? $order->order_details['grand_total'] : 'NOT_PRESENT';
                    $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                }

                $label = 'Items Total vs Order Total';
                $expected =  $total_row_total_incl_tax - $total_row_discount + $order->carriage;
                if ( $expected != 'DONOTCHECK' ) {
                    $actual = (isset($order->order_details['grand_total'])) ? $order->order_details['grand_total'] : 'NOT_PRESENT';
                    $message .= (isset($actual) && (round($actual,2) === round($expected,2))) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
                }


            }

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

    public function testUpdateOrderBasic() {
        $session = $this->_session;

        $requestBody = file_get_contents(__DIR__ . '/update_order.json');
         try {
            $updateOrderResponse = $session->updateOrder($this->_config['order_update_order_id'], $requestBody);
             $this->assertTrue($updateOrderResponse);
             $order = $session->getOrder($this->_config['order_update_order_id']);
             $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);

             $message = '';

             $label = 'Company_brand_code';
             $expected =  (isset($this->_config['order_update_company_brand_code']) ) ? $this->_config['order_update_company_brand_code'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->company_brand_code)) ? $order->company_brand_code : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

             $label = 'Company Order Reference';
             $expected =  (isset($this->_config['order_update_company_order_reference']) ) ? $this->_config['order_update_company_order_reference'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->company_order_reference)) ? $order->company_order_reference : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

             $label = 'Order id Brand';
             $expected =  (isset($this->_config['order_update_order_id_brand']) ) ? $this->_config['order_update_order_id_brand'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->order_id_brand)) ? $order->order_id_brand : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

             $label = 'Brand invoice address code';
             $expected =  (isset($this->_config['order_update_brand_invoice_address_code']) ) ? $this->_config['order_update_brand_invoice_address_code'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->brand_invoice_address_code)) ? $order->brand_invoice_address_code : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

             $label = 'Brand delivery address code';
             $expected =  (isset($this->_config['order_update_brand_delivery_address_code']) ) ? $this->_config['order_update_brand_delivery_address_code'] : 'DONOTCHECK';
             if ( $expected != 'DONOTCHECK' ) {
                 $actual = (isset($order->brand_delivery_address_code)) ? $order->brand_delivery_address_code : 'NOT_PRESENT';
                 $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
             }

             if ( !empty($message)) {
                 $message = "Order_id: " . $this->_config['order_update_order_id'] . ': ' . PHP_EOL . $message;
                 $this->fail($message);
             }


        } catch (\Manta\Exceptions\NoAccessException $e) {
             $this->fail('No access');
        }
    }

    public function testUpdateFailedOrder() {
        $session = $this->_session;

        $requestBody = file_get_contents(__DIR__ . '/update_order_error.json');
        try {
            $updateOrderResponse = $session->updateOrder($this->_config['order_with_access'], $requestBody);
            file_put_contents('/tmp/failed', var_export($updateOrderResponse, true));
            $this->assertTrue($updateOrderResponse);
            $order = $session->getOrder($this->_config['order_with_access']);
            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);

            $message = '';

            $label = 'Company_brand_code';
            $expected =  (isset($this->_config['order_update_company_brand_code']) ) ? $this->_config['order_update_company_brand_code'] : 'DONOTCHECK';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->company_brand_code)) ? $order->company_brand_code : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }


        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->fail('No access');
        }
    }

    public function testCreateOrderBasic() {
        $session = $this->_session;

        $requestBody = file_get_contents(__DIR__ . '/create_order_simple.json');
        //$requestBody = file_get_contents(__DIR__ . '/create_order.json');

        $requestBodyArray = json_decode($requestBody);


        if ( !isset($requestBodyArray->orders[0]->company_id) || empty($requestBodyArray->orders[0]->company_id) ) {
            $this->fail('Create order json not valid, unexpected json object:' . var_export($requestBodyArray->orders,true));
        }
        $this->assertTrue(true);



        try {
            $createOrderResponse = $session->createOrder($requestBody);

            file_put_contents('/tmp/order_return', var_export($createOrderResponse,true));

            if ( !isset($createOrderResponse['orders'][0]['order_id']) || empty($createOrderResponse['orders'][0]['order_id']) ) {
                $this->fail('Order not created, unexpected response, order_id not correct:' . var_export($createOrderResponse,true));
            }
            if ( !isset($createOrderResponse['orders'][0]['tmp_order_id']) || empty($createOrderResponse['orders'][0]['tmp_order_id']) ) {
                $this->assertTrue(false);
                $this->fail('Order not created, unexpected response:' . var_export($createOrderResponse,true));
            }
            $this->assertTrue(true);

            $orderId = $createOrderResponse['orders'][0]['order_id'];

            $order = $session->getOrder($orderId);

            $this->assertInstanceOf(\Manta\DataObjects\Objects\Order::class, $order);

            $message = '';

            $label = 'Company id';
            $expected =  (isset($requestBodyArray->order->company_id) ) ? $requestBodyArray->order->company_id : 'UNEXPECTED';
            if ( $expected != 'DONOTCHECK' ) {
                $actual = (isset($order->company_id)) ? $order->company_id : 'NOT_PRESENT';
                $message .= (isset($actual) && ($actual === $expected)) ? '' : PHP_EOL . $label . ' => Expected:  ' . $expected . ', Actual: ' . $actual;
            }



        } catch (\Manta\Exceptions\NoAccessException $e) {
            $this->fail('No access');
        }
    }




}