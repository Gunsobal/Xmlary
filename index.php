<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\XmlConverter;

$arr = [
    'Drinks' => [
        'Drink' => [
            [
                '@attributes' => ['Color' => 'Pink']
            ],
            'Cherry',
            'Apple',
            [
                '@attributes' => ['Color' => 'Green']
            ],
            'Lime',
            [
                '@attributes' => []
            ],
            'Coke',
            [
                '@attributes' => []
            ],
        ]
    ]
];
echo Xmlify::stringify($arr);
echo Xmlify::xmlify($arr)->saveXML();