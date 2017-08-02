<?php

include './src/Xmlify.php';
include './src/XmlParser.php';
include './src/XmlConverter.php';

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;

$arr = [
    'order' => [
        '@attributes' => ['class' => 'order'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
        'fruits' => [
            [
                '@attributes' => ['class' => 'fruit'] // Note: class="fruit" gets applied to all elements in the fruits array unless overwritten
            ],
            'orange',
            'banana',
            'apple'
        ],
        'drinks' => [
            '@attributes' => ['href' => 'myUrl'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
            'drink' => [
                [
                    '@attributes' => ['class' => 'red'], // Note: Attributes get applied to every subsequent drink node unless overwritten
                    'soda' => 'coke',
                    'healthy' => 'water'
                ],
                [
                    '@attributes' => ['class' => 'blue'],
                    'soda' => [
                        ['@attributes' => [ 'color' => 'pink' ]], // Note: To add attributes to an element, the element value needs to be an array containing an array with attributes key
                        'pepsi'
                    ],
                    'healthy' => 'juice',
                    'ingredients' => null
                ]
            ]
        ]
    ]
];

// echo '<pre>';
// print_r($arr);
// print_r(json_decode(json_encode($arr), true));

//  Xmlify::xmlify($arr)->saveXML();
