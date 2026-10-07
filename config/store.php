<?php
return [
    'domain'=>env('STORE_DOMAIN','aestheticstore.bd'),
    'phone'=>env('STORE_PHONE','01550720798'),
    'email'=>env('STORE_EMAIL','support@aestheticstore.bd'),
    'currency'=>env('STORE_CURRENCY','BDT'),
    'symbol'=>env('STORE_CURRENCY_SYMBOL','৳'),
    'dhaka_delivery'=>(int) env('DHAKA_DELIVERY_CHARGE',80),
    'outside_delivery'=>(int) env('OUTSIDE_DHAKA_DELIVERY_CHARGE',130),
];
