<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;
use Gunsobal\Xmlary\XmlMessage;

$arr = [
    'Message' => [
        'MyMessage' => [
            'a' => 'b',
            'c' => 'd'
        ],
        'MySecNode' => [
            'a' => 'b'
        ],
        'MyThird' => ''
    ]
];

echo Xmlify::xmlify($arr)->saveXML();
echo Xmlify::stringify($arr);