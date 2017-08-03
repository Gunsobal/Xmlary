<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;

echo '<pre>';
var_dump($msg);

// $arr = [
//     'order' => [
//         '@attributes' => ['class' => 'order', 'abc' => 'cde'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
//         'fruits' => [
//             [
//                 '@attributes' => ['class' => 'fruit', 'href' => 'ef'] // Note: class="fruit" gets applied to all elements in the fruits array unless overwritten
//             ],
//             [
//                 '@attributes' => ['class' => 'banana']
//             ],
//             [
//                 '@attributes' => ['class' => 'banana']
//             ],
//             'banana',
//             [
//                 '@attributes' => ['class' => 'apple']
//             ]
//         ],
//         'drinks' => [
//             '@attributes' => ['href' => 'myUrl'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
//             'drink' => [
//                 [
//                     '@attributes' => ['class' => 'red'], 
//                     'soda' => 'coke',
//                     'healthy' => 'water'
//                 ],
//                 [
//                     '@attributes' => ['class' => 'blue'],
//                     'soda' => [
//                         ['@attributes' => [ 'color' => 'pink' ]], // Note: To add attributes to an element, the element value needs to be an array containing an array with attributes key
//                         'pepsi'
//                     ],
//                     'healthy' => 'juice',
//                     'ingredients' => null
//                 ]
//             ]
//         ]
//     ]
// ];

// $arr2 = [
//     'Elem' => [
//         'Sub' => [
//             '@attributes' => ['class' => 'red']
//         ]
//     ]
// ];

// $s = XmlConverter::toDOM($arr);
// $assoc = XmlConverter::toAssoc($s);
// echo '<pre>';
// print_r($assoc);