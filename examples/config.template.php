<?php
return ['api_url' => 'https://expma.mantagifts.com/rest/V1/', /* The URL to the API, only for development purposes. */
        'username' => 'brand@example.com',//Your brand email/username
        'password' => 'password', //Clear text password.
        'debug' => false, //true or false. Will output extra request information if true
            //some variables that can be used for testing. The content depends on your brand set up.
        'company_without_access' => 20,
        'company_accessible' => 45,
        'order_without_access' => '4',
        'order_with_access' => '30'
];
