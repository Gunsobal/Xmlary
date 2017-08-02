<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';

use DOMDocument;

/**
 * Parse XML into JSON, Associative array, DOMDocument or SimpleXMLElement from xml string, SimpleXMLElement or DOMDocument
 *
 * @author Gunnar Baldursson
 */
class XmlParser
{
    public static function StringToSimple($str){
        return simplexml_load_string($str);
    }

    public static function StringToDOM($str){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($str);
        return $dom;
    }

    public static function StringToJson($str){
        return self::SimpleToJson(simplexml_load_string($str));
    }

    public static function StringToAssoc($str){
        return self::SimpleToAssoc(simplexml_load_string($str));
    }

    public static function DOMToSimple($dom){
        return simplexml_import_dom($dom);
    }

    public static function DOMToJson($dom){
        return self::SimpleToJson(self::DOMToSimple($dom));
    }

    public static function DOMToAssoc($dom){
        return self::SimpleToAssoc(self::DOMToSimple($dom));
    }

    public static function SimpleToDOM($simple){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXml($simple->asXML());
        return $dom;
    }

    public static function SimpleToJson($simple){
        return json_encode($simple);
    }

    public static function SimpleToAssoc($simple){
        return json_decode( json_encode($simple), true );
    }
}