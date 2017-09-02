<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for Xmlify
 */
class XmlifyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in Xmlify
     */
    public function testIsThereAnySyntaxError(){
        $x = new Xmlify;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Test stringify
     * @dataProvider provider
     */
    public function testStringify($arr){
        $this->assertTrue(is_string(Xmlify::stringify($arr)));
    }

    /**
     * Test htmlify
     * @dataProvider provider
     */
    public function testHtmlify($arr){
        $this->assertTrue(is_string(Xmlify::htmlify($arr)));
    }

    /**
     * Test xmlify
     * @dataProvider provider
     */
    public function testXmlify($arr){
        $this->assertTrue(is_a(Xmlify::xmlify($arr), 'DOMDocument'));
    }

    /**
     * Test fail
     * @expectedException Exception
     */
    public function testFail(){
        Xmlify::xmlify(0);
    }

    public function stringProvider(){
        $file = file_get_contents(__DIR__ . '\\..\\resources\\test.xml');
        return [ explode('%%', $file) ];
    }
}