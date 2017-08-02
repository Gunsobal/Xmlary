<?php

namespace Gunsobal\Xmlary;

include_once(dirname(__FILE__)."/../src/Utils.php");

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for XmlParser
 */
class XmlParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XmlParser
     */
    public function testIsThereAnySyntaxError(){
        $x = new XmlParser;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Testing StringToSimple
     * @dataProvider xmlProvider
     */
    public function testStringToSimple($str){
        $simple = XmlParser::StringToSimple($str);
        $this->assertTrue(is_a($simple, 'SimpleXMLElement'));
        return $simple;
    }

    /**
     * Testing StringToDOM
     * @dataProvider xmlProvider
     */
    public function testStringToDOM($str){
        $dom = XmlParser::StringToDOM($str);
        $this->assertTrue(is_a($dom, 'DOMDocument'));
        return $dom;
    }

    /**
     * Testing StringToJson
     * @dataProvider xmlProvider
     */
    public function testStringToJson($str){
        $this->assertTrue(Utils::isJson(XmlParser::StringToJson($str)));
    }

    /**
     * Testing StringToAssoc
     * @dataProvider xmlProvider
     */
    public function testStringToAssoc($str){
        $this->assertTrue(is_array(XmlParser::StringToAssoc($str)));
    }

    /**
     * Testing DOMToSimple
     * @dataProvider xmlProvider
     */
    public function testDOMToSimple($str){
        $dom = XmlParser::StringToDOM($str);
        $this->assertTrue(is_a(XmlParser::DOMToSimple($dom), 'SimpleXMLElement'));
    }

    /**
     * Testing DOMToJson
     * @dataProvider xmlProvider
     */
    public function testDOMToJson($str){
        $dom = XmlParser::StringToDOM($str);
        $this->assertTrue(Utils::isJson(XmlParser::DOMToJson($dom)));
    }

    /**
     * Testing DOMToAssoc
     * @dataProvider xmlProvider
     */
    public function testDOMToAssoc($str){
        $dom = XmlParser::StringToDOM($str);
        $this->assertTrue(Utils::isStringKeyed(XmlParser::DOMToAssoc($dom)));
    }

    /**
     * Testing SimpleToDOM
     * @dataProvider xmlProvider
     */
    public function testSimpleToDOM($str){
        $s = XmlParser::StringToSimple($str);
        $this->assertTrue(is_a(XmlParser::SimpleToDOM($s), 'DOMDocument'));
    }

    /**
     * Testing SimpleToJson
     * @dataProvider xmlProvider
     */
    public function testSimpleToJson($str){
        $s = XmlParser::StringToSimple($str);
        $this->assertTrue(Utils::isJson(XmlParser::SimpleToJson($s)));
    }

    /**
     * Testing SimpleToAssoc
     * @dataProvider xmlProvider
     */
    public function testSimpleToAssoc($str){
        $s = XmlParser::StringToSimple($str);
        $this->assertTrue(Utils::isStringKeyed(XmlParser::SimpleToAssoc($s)));
    }

    public function xmlProvider(){
        return [
            ['<?xml version="1.0" encoding="UTF-8"?><Element><Sub>Val</Sub><Sub>V</Sub></Element>']
        ];
    }
}