<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';

use DOMDocument;

/**
 * Parse XML to JSON, Associative array, DOMDocument or SimpleXMLElement from xml string, SimpleXMLElement or DOMDocument
 *
 * @author Gunnar Baldursson
 */
class XmlParser
{
    /**
     * Parses xml string to SimpleXMLElement
     * @param string $str XML String
     * @return \SimpleXMLElement
     */
    public static function StringToSimple($str){
        return simplexml_load_string($str);
    }

    /**
     * Parses xml string to DOMDocument
     * @param string $str XML string
     * @return \DOMDocument
     */
    public static function StringToDOM($str){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($str);
        return $dom;
    }

    /**
     * Parses xml string to JSON string
     * @param string $str XML string
     * @return string
     */
    public static function StringToJson($str){
        return self::SimpleToJson(simplexml_load_string($str));
    }

    /**
     * Parses xml string to associative array
     * @param string $str XML string
     * @return array
     */
    public static function StringToAssoc($str){
        return self::SimpleToAssoc(simplexml_load_string($str));
    }

    /**
     * Parses DOMDocument to SimpleXMLElement
     * @param \DOMDocument $dom
     * @return \SimpleXMLElement
     */
    public static function DOMToSimple($dom){
        return simplexml_import_dom($dom);
    }

    /**
     * Parses DOMDocument to JSON string
     * @param \DOMDocument
     * @return string
     */
    public static function DOMToJson($dom){
        return self::SimpleToJson(self::DOMToSimple($dom));
    }

    /**
     * Parses DOMDocument to associative array
     * @param \DOMDocument
     * @return array
     */
    public static function DOMToAssoc($dom){
        return self::SimpleToAssoc(self::DOMToSimple($dom));
    }

    /**
     * Parses SimpleXMLElement to DOMDocument
     * @param \SimpleXMLElement $simple
     * @return \DOMDocument
     */
    public static function SimpleToDOM($simple){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXml($simple->asXML());
        return $dom;
    }

    /**
     * Parses SimpleXMLElement to JSON string
     * @param \SimpleXMLElement $simple
     * @return string
     */
    public static function SimpleToJson($simple){
        return json_encode($simple);
    }

    /**
     * Parses SimpleXMLElement to associative array
     * @param \SimpleXMLElement $simple
     * @return array
     */
    public static function SimpleToAssoc($simple){
        return json_decode( json_encode($simple), true );
    }
}