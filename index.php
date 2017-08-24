<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlParser;

$arr = [
    'Messages' => [
        'Message' => [
            ['@attributes' => ['class' => 'important']],
            'Abs',
            ['@attributes' => []],
            'String2',
            'String3'
        ],
        'ComplexMessage' => [
            [
                '@attributes' => ['class' => 'highlighted'],
                'Header' => 'MyMessage',
                'Body' => [
                    'Title' => 'abc',
                    'Text' => [
                        [
                            'p' => 'text'
                        ],
                        [
                            'p' => 'more text'
                        ]
                    ]
                ]
            ],
            [
                'Body' => 'Lucifer',
                'Footer' => 'boner'
            ]
        ]
    ]
];

function stc($simple){
    $assoc = [];
    foreach ($simple as $x){
        if (count($x->children())){
            $arr = [];
            foreach ($x->children() as $key => $c){
                $attrs = [];
                foreach ($x->attributes() as $a => $b){
                    $attrs[$a] = (string) $b;
                }
                $arr['@attributes'] = $attrs;
                if (count($c->children())){
                    $arr[$key] = stc($c)[$key];
                } else {
                    $arr[$key] = (string) $c;
                }
            }
            $assoc[$simple->getName()][$x->getName()][] = $arr;
        } else {
            if (count($x->attributes())){
                foreach ($x->attributes() as $a => $b){
                    $assoc[$simple->getName()][$x->getName()][] = ['@attributes' => [$a => (string) $b]];
                }
            } else {
                $assoc[$simple->getName()][$x->getName()][] = ['@attributes' => []];
            }
            $assoc[$simple->getName()][$x->getName()][] = (string) $x;
        }
    }
    return $assoc;
}

$xml = Xmlify::xmlify($arr);
$xml = simplexml_import_dom($xml);
$a = json_decode(XmlParser::SimpleToJson($xml), true);
$x = Xmlify::xmlify($a);

$x = simplexml_import_dom($x);

if ($x->asXML() == $xml->asXML()){
    echo "True";
} else {
    echo "False";
}