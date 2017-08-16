<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;

$arr = [
    'Order' => [
        '@attributes' => [
            'id' => 1,
            'payment_method' => 'visa'
        ],
        'Products' => [
            'Product' => [
                [
                    '@attributes' => ['valid' => true, 'limited' => false],
                    'Number' => 1,
                    'Element' => [
                        '@attributes' => [
                            'name' => 'Tshirt'
                        ]
                    ]
                ],
                [
                    'Number' => 2,
                    'Element' => [
                        '@attributes' => [
                            'name' => 'Pants'
                        ]
                    ]
                ],
            ]
        ]
    ]
];
echo Xmlify::xmlify($arr)->saveXML();
echo Xmlify::stringify($arr);