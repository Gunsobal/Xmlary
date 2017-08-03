<?php

include './src/Xmlify.php';
include './src/XmlParser.php';
include './src/XmlConverter.php';

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;

$arr = [
    'order' => [
        '@attributes' => ['class' => 'order', 'abc' => 'cde'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
        'fruits' => [
            [
                '@attributes' => ['class' => 'fruit', 'href' => 'ef'] // Note: class="fruit" gets applied to all elements in the fruits array unless overwritten
            ],
            [
                '@attributes' => ['class' => 'banana']
            ],
            [
                '@attributes' => ['class' => 'banana']
            ],
            'banana',
            [
                '@attributes' => ['class' => 'apple']
            ]
        ],
        'drinks' => [
            '@attributes' => ['href' => 'myUrl'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
            'drink' => [
                [
                    '@attributes' => ['class' => 'red'], 
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

$arr2 = [
    'Elem' => [
        'Sub' => [
            '@attributes' => ['class' => 'red']
        ]
    ]
];

echo Xmlify::xmlify($arr)->saveXML();
echo Xmlify::stringify($arr);

echo Xmlify::xmlify($arr2)->saveXML();
echo Xmlify::stringify($arr2);

// XmlConverter tests
// $simple = XmlConverter::toSimple($arr); // Correct
// $simple2 = XmlConverter::toSimple($arr2); // Correct

// $dom = XmlConverter::toDOM($arr); // Correct
// $dom2 = XmlConverter::toDOM($arr2); // Correct

// $json = XmlConverter::toJson($arr); // Looks correct
// $json2 = XmlConverter::toJson($arr2); // Looks correct

// $assoc = XmlConverter::toAssoc($simple);
// $assoc_1 = XmlConverter::toAssoc($dom);
// $assoc_2 = XmlConverter::toAssoc($json);

// echo $simple->asXML();
// echo $simple2->asXML();
// echo $dom->saveXML();
// echo $dom2->saveXML();
// echo $json;
// echo $json2;
// echo '<pre>';
// print_r($assoc);