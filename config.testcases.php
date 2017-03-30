<?php

/* Change this file is you want to use the phpunit testcase functionality */

return [
    'company_without_access' => 63, /* Try retrieve a company which has NOT placed an order for the specific brand */
    'company_accessible' => 38, /* Try retrieve a company which has placed an order for the specific brand */
    'order_without_access' => '88', /* Try retrieve a order from another brand */
    'order_without_access_status' => '95', /* Try retrieve a order from the brand with an not allowed status (not allowed to be seen by the brand) */
    'order_with_access' => '100' /* Try retrieve a order from the brand */
];