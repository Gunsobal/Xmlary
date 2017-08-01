<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding test class to XmlParser class
 *
 * Unit tests for XmlParser
 */
class XmlParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XmlParser
     *
     * Simple check for typos and such
     */
    public function testIsThereAnySyntaxError(){
        $x = new XmlParser;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Testing toJson
     *
     * Test success cases for toJson function
     * 
     * @dataProvider xmlProvider
     */
    public function testToJson($arr){
        $this->assertTrue(is_string(XmlParser::toJson($arr)));
        $this->assertTrue(is_string(XmlParser::toJson(\simplexml_load_string($arr))));
        $dom = new \DOMDocument();
        $dom->loadXML($arr);
        $this->assertTrue(is_string(XmlParser::toJson($dom)));
    }

    /**
     * Testing toJson
     *
     * Test fail case for toJson function
     * 
     * @expectedException Exception
     */
    public function testToJsonfail(){
        XmlParser::toJson(9);
    }

    /**
     * Testing toAssoc
     *
     * Test success cases for toAssoc function
     * 
     * @dataProvider xmlProvider
     */
    public function testToAssoc($arr){
        $this->assertTrue(is_array(XmlParser::toAssoc($arr)));
        $this->assertTrue(is_array(XmlParser::toAssoc(\simplexml_load_string($arr))));
        $dom = new \DOMDocument();
        $dom->loadXML($arr);
        $this->assertTrue(is_array(XmlParser::toAssoc($dom)));
    }

    /**
     * Testing toAssoc
     *
     * Test fail case for toAssoc function
     * 
     * @expectedException Exception
     */
    public function testToAssocFail(){
        XmlParser::toAssoc(9);
    }

    /**
     * Testing toSimple
     *
     * Test success cases for toSimple function
     *
     * @dataProvider xmlProvider
     */
    public function testToSimple($arr){
        $this->assertTrue(is_a(XmlParser::toSimple($arr), 'SimpleXMLElement'));
        $this->assertTrue(is_a(XmlParser::toSimple(\simplexml_load_string($arr)), 'SimpleXMLElement'));
        $dom = new \DOMDocument();
        $dom->loadXML($arr);
        $this->assertTrue(is_a(XmlParser::toSimple($dom), 'SimpleXMLElement'));
    }

    /**
     * Testing toSimple
     *
     * Test fail case for toSimple function
     * 
     * @expectedException Exception
     */
    public function testToSimpleFail(){
        XmlParser::toSimple(9);
    }

    /**
     * Testing toDOM
     *
     * Test success cases for toDOM function
     *
     * @dataProvider xmlProvider
     */
    public function testToDOM($arr){
        $this->assertTrue(is_a(XmlParser::toDOM($arr), 'DOMDocument'));
        $this->assertTrue(is_a(XmlParser::toDOM(\simplexml_load_string($arr)), 'DOMDocument'));
        $dom = new \DOMDocument();
        $dom->loadXML($arr);
        $this->assertTrue(is_a(XmlParser::toDOM($dom), 'DOMDocument'));
    }

    /**
     * Testing toDOM
     *
     * Test fail case for toDOM function
     * 
     * @expectedException Exception
     */
    public function testToDOMFail(){
        XmlParser::toDOM(9);
    }


    public function xmlProvider(){
        return [
            [
                '<root>element</root>'
            ]
        ];
    }
}