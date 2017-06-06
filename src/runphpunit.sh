#!/bin/bash

#./vendor/bin/phpunit --configuration phpunit.xml
#./vendor/bin/phpunit --filter testGetOrderDetails --configuration phpunit.xml
#./vendor/bin/phpunit --filter testUpdateFailedOrder --configuration phpunit.xml
#./vendor/bin/phpunit --filter testUpdateOrderBasic --configuration phpunit.xml
#./vendor/bin/phpunit --filter testGetOrderStatusEqualTo --configuration phpunit.xml
#./vendor/bin/phpunit --filter testCreateOrderBasic --configuration phpunit.xml
#./vendor/bin/phpunit --filter testCreatePoOrderBasic --configuration phpunit.xml
./vendor/bin/phpunit --filter testCreateInvoiceBasic --configuration phpunit.xml