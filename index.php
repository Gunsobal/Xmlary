<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;

$arr = [
    'Message' => [
        'abc',
        'cde'
    ]
];

echo Xmlify::stringify($arr);