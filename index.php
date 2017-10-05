<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

$a = [
    'Message' => [
        'Body' => [
            'Text' => 'Value'
        ],
        'Footer' => [
            'Author' => [
                'Title' => 'Developer',
                'Name' => 'Gunnar'
            ]
        ]
    ]
];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <pre><?= Xmlify::htmlify($a) ?></pre>
</body>
</html>