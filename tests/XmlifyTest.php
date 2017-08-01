<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding test class to Xmlify class
 *
 * Unit tests for Xmlify
 */
class XmlifyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in Xmlify
     *
     * Simple check for typos and such
     */
    public function testIsThereAnySyntaxError(){
        $x = new Xmlify;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Testing stringify function in Xmlify
     *
     * Testing a complex success case in stringify function
     * 
     * @dataProvider provider
     */
    public function testStringify($arr){
        $this->assertTrue(strlen(Xmlify::stringify($arr)) == 358);
    }
    
    /**
     * Testing stringify_html function in Xmlify
     *
     * Testing a complex success case in stringify_html function
     * 
     * @dataProvider provider
     */
    public function testStringifyHtml($arr){
        $this->assertTrue(strlen(Xmlify::stringify_html($arr)) == 570);
    }

    /**
     * Testing xmlify_array function in Xmlify
     *
     * Testing a complex success case in xmlify_array function
     *
     * @dataProvider provider
     */
     public function testXmlify($arr){
         $this->assertTrue(get_class(Xmlify::xmlify_array($arr)) == 'DOMDocument');
     }

    /**
     * Testing stringify function in Xmlify
     *
     * Testing a fail case in stringify function
     *
     * @expectedException Exception
     */
    public function testStringifyFail(){
        Xmlify::stringify(['xml' => 'value']);
    }

    /**
     * Testing xmlify_array function in Xmlify
     *
     * Testing a fail case in xmlify_array function
     *
     * @expectedException Exception
     */
    public function testXmlifyFail(){
        Xmlify::xmlify_array(['xml' => 'value']);
    }

    /** 
     * Provides large complex success case
     */
    public function provider(){
        return [
            [
                ['order' => [
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
                ]
            ]
        ];
    }
}
