<?php

namespace Gunsobal\Xmlary;

/**
 * Class converts between XML, associative arrays and json
 *
 * This class' role is to provide an easy interface to convert between arrays, xml and json
 *
 * @author Gunso Baldursson
 */
class XmlConverter
{
    /**
     * Parse XML to JSON from string, DOMDocument or SimpleXMLElement
     * @param mixed $xml string, DOMDocument or SimpleXMLElement
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
    }

    /**
     * Parse XML to associative array from string, DOMDoucment or SimpleXMLElement
     * @param mixed $xml string, DOMDocument or SimpleXMLElement
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
    }

    /**
     * Parse XML to DOMDocument from string or SimpleXMLElement
     * @param mixed $xml string or SimpleXMLElement
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
    }

    /**
     * Parse XML to SimpleXMLElement from string or DOMDocument
     * @param mixed $xml string or DOMDocument
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
    }
}