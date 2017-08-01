<?php

include 'src/xmlify.php';

$xmlify = new Gunsobal\Xmlary\Xmlify();

$arr = [
    'order' => [
        '@attributes' => ['class' => 'order'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
        'fruits' => [
            [
                '@attributes' => ['class' => 'fruit'] // Note: class="fruit" gets applied to all elements in the fruits array unless overwritten
            ],
            'orange',
            'banana',
            [
                '@attributes' => ['class' => 'fruit red', 'id' => 5] //Note: Overwriting attributes
            ],
            'apple'
        ],
        'drinks' => [
            '@attributes' => ['href' => 'myUrl'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
            'drink' => [
                ['@attributes' => ['class' => 'red']], // Note: Attributes get applied to every subsequent drink node unless overwritten
                [
                    'soda' => 'coke',
                    'healthy' => 'water'
                ],
                ['@attributes' => []], //Note: Overwriting attributes to empty
                [
                    'soda' => [
                        ['@attributes' => [ 'color' => 'pink' ]], // Note: To add attributes to an element, the element value needs to be an array containing an array with attributes key
                        'pepsi'
                    ],
                    'healthy' => 'juice'
                ]
            ]
        ]
    ]
];

$str = Gunsobal\Xmlary\Xmlify::stringify_html($arr);
echo strlen($str);