<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';

use DOMDocument;

/**
 * Class parses XML
 *
 * Parse XML into JSON, Associative array, DOMDocument or SimpleXMLElement from xml string, SimpleXMLElement or DOMDocument
 *
 * @author Gunnar Baldursson
 */
class XmlParser
{
    /**
     * Parse XML to JSON
     *
     * Parse XML to JSON from string, DOMDocument or SimpleXMLElement
     *
     * @param mixed $xml string, DOMDocument or SimpleXMLElement
     *
     * @return string
     */
    public static function toJson($xml){
        if (is_string($xml)){
            return self::StringToJson($xml);
        }
        if (is_a($xml, 'DOMDocument')){
            return self::DOMToJson($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return self::SimpleToJson($xml);
        }
        throw new XmlParserException("Type of xml not recognized");
    }

    /**
     * Parse XML to associative array
     *
     * Parse XML to associative array from string, DOMDoucment or SimpleXMLElement
     *
     * @param mixed $xml string, DOMDocument or SimpleXMLElement
     *
     * @return array
     */
    public static function toAssoc($xml){
        if (is_string($xml)){
            return self::StringToAssoc($xml);
        }
        if (is_a($xml, 'DOMDocument')){
            return self::DOMToAssoc($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return self::SimpleToAssoc($xml);
        }
        throw new XmlParserException("Type of xml not recognized");
    }

    /**
     * Parse XML to DOMDocument
     *
     * Parse XML to DOMDocument from string or SimpleXMLElement
     *
     * @param mixed $xml string or SimpleXMLElement
     *
     * @return \DOMDocument
     */
    public static function toDOM($xml){
        if (is_string($xml)){
            return self::StringToDOM($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return self::SimpleToDOM($xml);
        }
        if (is_a($xml, 'DOMDocument')){
            return $xml;
        }
        throw new XmlParserException("Type of xml not recognized");
    }

    /**
     * Parse XML to SimpleXMLElement
     *
     * Parse XML to SimpleXMLElement from string or DOMDocument
     *
     * @param mixed $xml string or DOMDocument
     *
     * @return \SimpleXMLElement
     */
    public static function toSimple($xml){
        if (is_string($xml)){
            return self::StringToSimple($xml);
        }
        if (is_a($xml, 'DOMDocument')){
            return self::DOMToSimple($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return $xml;
        }
        throw new XmlParserException("Type of xml not recognized");
    }

    protected static function StringToSimple($str){
        return simplexml_load_string($str);
    }

    protected static function StringToDOM($str){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($str);
        return $dom;
    }

    protected static function StringToJson($str){
        return self::SimpleToJson(simplexml_load_string($str));
    }

    protected static function StringToAssoc($str){
        return self::SimpleToAssoc(simplexml_load_string($str));
    }

    protected static function DOMToSimple($dom){
        return simplexml_import_dom($dom);
    }

    protected static function DOMToJson($dom){
        return self::SimpleToJson(self::DOMToSimple($dom));
    }

    protected static function DOMToAssoc($dom){
        return self::SimpleToAssoc(self::DOMToSimple($dom));
    }

    protected static function SimpleToDOM($simple){
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXml($simple->asXML());
        return $dom;
    }

    protected static function SimpleToJson($simple){
        return json_encode($simple);
    }

    protected static function SimpleToAssoc($simple){
        return json_decode( json_encode($simple), true );
    }
}