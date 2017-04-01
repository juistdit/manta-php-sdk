<?php

/* Change this file is you want to use the phpunit testcase functionality */

$aConfigTemp =  [
    /* COMPANY TESTS */
    'company_without_access' => 63, /* Try retrieve a company which has NOT placed an order for the specific brand */

    'company_accessible' => 38, /* Try retrieve a company which has placed an order for the specific brand */
    'company_accessible_company_name' => 'SDK Test Company', /* Expected name of the company which should be retrieved */
    'company_accessible_shop_name' => 'SDK Test Shop', /* Expected name of the company which should be retrieved */

    /* ORDER TESTS */
    'order_without_access' => '94', /* Try retrieve a order from another brand */
    'order_without_access_status' => '95', /* Try retrieve a order from the brand with an not allowed status (not allowed to be seen by the brand) */

    // Check correctness values
    'order_with_access' => '111', /* Try retrieve a order from the brand */
    'order_accessible_company_name' => 'SDK Test Company', /* Expected name of the company which should be retrieved */
    'order_accessible_buyer_contact_id' => '36',
    'order_accessible_invoice_contact_id' => '36',
    'order_accessible_delivery_contact_id' => '36',
    'order_accessible_invoice_address_id' => '52',
    'order_accessible_delivery_address_id' => '127',
    'order_accessible_preferred_delivery_date' => '2017-04-01',
    'order_accessible_invoice_address' => 'Wilhelminapark 22',
    'order_accessible_delivery_address' => 'Wilhelminapark 22b',


    'order_accessible_sales_person' => 'Jurn Raaijmakers',
    'order_accessible_payment_terms' => '7_days_net',
    'order_accessible_brand_remark' => 'This is a remark',
    'order_accessible_company_order_reference' => '4747',
    'order_accessible_order_id_brand' => '1001',
    'order_accessible_brand_invoice_address_code' => '1002',
    'order_accessible_brand_delivery_address_code' => '1003',
    'order_accessible_order_location' => 'Some where',
    'order_accessible_payment_method' => 'Method',
    'order_accessible_brand_discount' => '7',
    'order_accessible_status' => 'new',
    'order_accessible_total_item_count' =>4,
];

/* Items */
$sku = 'SKNUTROBOTL1';
$aConfigTemp['order_accessible_items'][$sku]['original_price']  = 15.95;
$aConfigTemp['order_accessible_items'][$sku]['discount_percent']  = 15;
$aConfigTemp['order_accessible_items'][$sku]['qty_ordered']  = 6;
$aConfigTemp['order_accessible_items'][$sku]['tax_amount']  = 0;
$aConfigTemp['order_accessible_items'][$sku]['row_total']  = $aConfigTemp['order_accessible_items'][$sku]['original_price'] * $aConfigTemp['order_accessible_items'][$sku]['qty_ordered'];
$aConfigTemp['order_accessible_items'][$sku]['row_total_net'] = ( $aConfigTemp['order_accessible_items'][$sku]['row_total'] / 100 * (100 - $aConfigTemp['order_accessible_items'][$sku]['discount_percent']) );
$aConfigTemp['order_accessible_items'][$sku]['row_total_incl_tax_minus_discount']  = round($aConfigTemp['order_accessible_items'][$sku]['row_total_net'] + $aConfigTemp['order_accessible_items'][$sku]['tax_amount'],2);


/* Totals */
$aConfigTemp['order_accessible_subtotal'] = 273.94;
$aConfigTemp['order_accessible_carriage'] = 10.00;
$aConfigTemp['order_accessible_tax_amount'] = 0;
$aConfigTemp['order_accessible_grand_total'] = $aConfigTemp['order_accessible_subtotal'] + $aConfigTemp['order_accessible_tax_amount'] + $aConfigTemp['order_accessible_carriage'];
$aConfigTemp['order_accessible_grand_total_minus_carriage'] = $aConfigTemp['order_accessible_subtotal'] + $aConfigTemp['order_accessible_tax_amount'] ;

return $aConfigTemp;