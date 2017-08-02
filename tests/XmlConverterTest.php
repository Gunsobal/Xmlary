<?php

namespace Gunsobal\Xmlary;

include_once(dirname(__FILE__)."/../src/Utils.php");

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for XmlConverter
 */
class XmlConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XmlConverter
     */
    public function testIsThereAnySyntaxError(){
        $x = new XmlConverter;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Test to funcs with string input
     * @dataProvider stringProvider
     */
    public function testWithString($str){
        $this->assertTrue(Utils::isJson(XmlConverter::toJson($str)));
        $this->assertTrue(Utils::isStringKeyed(XmlConverter::toAssoc($str)));
        $this->assertTrue(is_a(XmlConverter::toDOM($str), 'DOMDocument'));
        $this->assertTrue(is_a(XmlConverter::toSimple($str), 'SimpleXMLElement'));
    }

    /** 
     * Test to funcs with dom input
     * @dataProvider stringProvider
     */
    public function testWithDOM($str){
        $dom = XmlConverter::toDom($str);
        $this->assertTrue(Utils::isJson(XmlConverter::toJson($dom)));
        $this->assertTrue(Utils::isStringKeyed(XmlConverter::toAssoc($dom)));
        $this->assertTrue(is_a(XmlConverter::toDOM($dom), 'DOMDocument'));
        $this->assertTrue(is_a(XmlConverter::toSimple($dom), 'SimpleXMLElement'));
    }

    /**
     * Test to funcs with simple input
     * @dataProvider stringProvider
     */
    public function testWithSimple($str){
        $sim = XmlConverter::toSimple($str);
        $this->assertTrue(Utils::isJson(XmlConverter::toJson($sim)));
        $this->assertTrue(Utils::isStringKeyed(XmlConverter::toAssoc($sim)));
        $this->assertTrue(is_a(XmlConverter::toDOM($sim), 'DOMDocument'));
        $this->assertTrue(is_a(XmlConverter::toSimple($sim), 'SimpleXMLElement'));
    }

    /**
     * Test to funcs with json input
     * @dataProvider stringProvider
     */
    public function testWithJson($str){
        $json = XmlConverter::toJson($str);
        $this->assertTrue(Utils::isJson(XmlConverter::toJson($json)));
        $this->assertTrue(Utils::isStringKeyed(XmlConverter::toAssoc($json)));
        $this->assertTrue(is_a(XmlConverter::toDOM($json), 'DOMDocument'));
        $this->assertTrue(is_a(XmlConverter::toSimple($json), 'SimpleXMLElement'));
    }

    /**
     * Test to funcs with array input
     * @dataProvider stringProvider
     */
    public function testWithArray($str){
        $arr = XmlConverter::toAssoc($str);
        $this->assertTrue(Utils::isJson(XmlConverter::toJson($arr)));
        $this->assertTrue(Utils::isStringKeyed(XmlConverter::toAssoc($arr)));
        $this->assertTrue(is_a(XmlConverter::toDOM($arr), 'DOMDocument'));
        $this->assertTrue(is_a(XmlConverter::toSimple($arr), 'SimpleXMLElement'));
    }

    public function stringProvider(){
        return [
            ['<?xml version="1.0" encoding="UTF-8"?><Element><Sub>Val</Sub><Sub>V</Sub></Element>']
        ];
    }
}
