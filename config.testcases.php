<?php

/* Change this file is you want to use the phpunit testcase functionality */

return [
    /* COMPANY TESTS */
    'company_without_access' => 63, /* Try retrieve a company which has NOT placed an order for the specific brand */

    'company_accessible' => 38, /* Try retrieve a company which has placed an order for the specific brand */
    'company_accessible_company_name' => 'SDK Test Company', /* Expected name of the company which should be retrieved */
    'company_accessible_shop_name' => 'SDK Test Shop', /* Expected name of the company which should be retrieved */

    /* ORDER TESTS */
    'order_without_access' => '94', /* Try retrieve a order from another brand */
    'order_without_access_status' => '95', /* Try retrieve a order from the brand with an not allowed status (not allowed to be seen by the brand) */

    // Check correctness values
    'order_with_access' => '108', /* Try retrieve a order from the brand */
    'order_accessible_company_name' => 'SDK Test Company', /* Expected name of the company which should be retrieved */
    'order_accessible_buyer_contact_id' => '36',
    'order_accessible_invoice_contact_id' => '36',
    'order_accessible_delivery_contact_id' => '36',
    'order_accessible_invoice_address_id' => '52',
    'order_accessible_delivery_address_id' => '127',
    'order_accessible_preferred_delivery_date' => '2017-03-31',
    'order_accessible_invoice_address' => 'Wilhelminapark 22',
    'order_accessible_delivery_address' => 'Wilhelminapark 22b',
    'order_accessible_carriage' => '10.00',


];