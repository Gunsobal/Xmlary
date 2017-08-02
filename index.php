<?php

include './src/Xmlify.php';

use Gunsobal\Xmlary\Xmlify;

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

// <order class="order">
//   <fruits class="fruit">orange</fruits>
//   <fruits class="fruit">banana</fruits>
//   <fruits class="fruit">apple</fruits>
//   <drinks href="myUrl">
//     <drink class="red">
//       <soda>coke</soda>
//       <healthy>water</healthy>
//     </drink>
//     <drink>
//       <soda color="pink">pepsi</soda>
//       <healthy>juice</healthy>
//     </drink>
//   </drinks>
// </order>

$arr2 = json_decode(json_encode($arr), true);
echo Xmlify::xmlify($arr2)->saveXML();
echo Xmlify::stringify($arr);
// echo Xmlify::stringify($arr2);
