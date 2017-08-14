<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;
use Gunsobal\Xmlary\XmlMessage;

$arr = [
    'Message' => [
        'MyMessage' => [
            'a' => true,
            'c' => false,
        ],
        'MySecNode' => [
            'a' => true
        ],
        'MyThird' => null
    ]
];

echo Xmlify::xmlify($arr)->saveXML();
echo Xmlify::stringify($arr);