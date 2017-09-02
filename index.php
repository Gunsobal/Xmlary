<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\XmlConverter;

$arr = [
                'Message' => [
                    '@attributes' => ['class' => 'testmessage'],
                    'Subject' => [],
                    'Content' => [
                        '@attributes' => ['class' => 'sigh'],
                        'Images' => ['Image' => [
                                ['@attributes' => ['figure' => 'img']],
                                'a.png',
                                'b.png',
                                ['@attributes' => []], '',
                                [
                                    'Pic' => [
                                        'A' => [
                                            ['@attributes' => ['href' => 'a']], 'C'
                                        ],
                                        'B' => 'D'
                                    ]
                                ]
                            ]
                        ],
                        'Text' => [
                            [
                                ['@attributes' => ['test' => false, 'title' => 'b']],
                                [
                                    'Header' => [
                                        '@attributes' => ['hr' => 'hs'],
                                        'h1' => 'Bla'
                                    ],
                                    'Body' => [
                                        ['@attributes' => ['body' => 'page']],
                                        'P1',
                                        ['@attributes' => []],
                                        'P2'
                                    ]
                                ],
                                ['@attributes' => ['a' => 'b']], []
                            ]
                        ]
                    ]
                ]
            ];

$s1 = Xmlify::stringify($arr);
echo $s1;
die() ;
$s2 = '<?xml version="1.0"?>
<Simple class="1">
  <Test p="true">Singlest</Test>
</Simple>';
$x1 = simplexml_load_string($s1);
$x2 = simplexml_load_string($s2);
echo trim($x1->asXML());
echo trim($x2->asXML());