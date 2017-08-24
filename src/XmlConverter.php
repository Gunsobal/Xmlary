<?php

namespace Gunsobal\Xmlary;

use Gunsobal\Xmlary\XmlParser;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Utils\Strings;
use Gunsobal\Utils\Arrays;

/**
 * This class' role is to provide a convenient API to convert between arrays, XML and Json
 *
 * @author Gunnar Baldursson
 */
class XmlConverter
{
    /**
     * Convert XML to JSON string from string, DOMDocument, SimpleXMLElement or string keyed array
     * @param mixed $xml string, DOMDocument, SimpleXMLElement or string keyed array
     * @return string
     */
    public static function toJson($xml){
        if (Strings::isJson($xml)){
            return $xml;
        }
        if (is_string($xml)){
            return XmlParser::StringToJson($xml);
        }
        if (is_a($xml, 'DOMDocument')){
            return XmlParser::DOMToJson($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return XmlParser::SimpleToJson($xml);
        }
        if (Arrays::isStringKeyed($xml)){
            return json_encode($xml);
        }
        throw new XmlConverterException("Unknown parameter in toJson function");
    }

    /**
     * Parse XML to string keyed array from string, DOMDoucment, JSON string or SimpleXMLElement
     * @param mixed $xml string, DOMDocument, JSON string or SimpleXMLElement
     * @return array
     */
    public static function toAssoc($xml){
        if (Arrays::isStringKeyed($xml)){
            return $xml;
        }
        if (is_a($xml, 'DOMDocument')){
            return XmlParser::DOMToAssoc($xml);
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return XmlParser::SimpleToAssoc($xml);
        }
        if (Strings::isJson($xml)){
            return json_decode($xml, true);
        }
        if (is_string($xml)){
            return XmlParser::StringToAssoc($xml);
        }
        throw new XmlConverterException("Unknown parameter in toAssoc function");
    }

    /**
     * Parse XML to DOMDocument from string, SimpleXMLElement, JSON string or string keyed array
     * @param mixed $xml string, SimpleXMLElement, JSON string or string keyed array
     * @return \DOMDocument
     */
    public static function toDOM($xml){
        if (is_a($xml, 'DOMDocument')){
            return $xml;
        }
        if (is_a($xml, 'SimpleXMLElement')){
            return XmlParser::SimpleToDOM($xml);
        }
        if (Arrays::isStringKeyed($xml)){
            return Xmlify::xmlify($xml);
        }
        if (Strings::isJson($xml)){
            return Xmlify::xmlify(json_decode($xml, true));
        }
        if (is_string($xml)){
            return XmlParser::StringToDOM($xml);
        }
        throw new XmlConverterException("Unknown parameter in toDOM function");
    }

    /**
     * Parse XML to SimpleXMLElement from string, JSON string, string keyed array or DOMDocument
     * @param mixed $xml string, JSON string, string keyed array or DOMDocument
     * @return \SimpleXMLElement
     */
    public static function toSimple($xml){
        if (is_a($xml, 'SimpleXMLElement')){
            return $xml;
        }
        if (is_a($xml, 'DOMDocument')){
            return XmlParser::DOMToSimple($xml);
        }
        if (Arrays::isStringKeyed($xml)){
            return XmlParser::DOMToSimple(Xmlify::xmlify($xml));
        }
        if (Strings::isJson($xml)){
            return XmlParser::DOMToSimple(Xmlify::xmlify(json_decode($xml, true)));
        }
        if (is_string($xml)){
            return XmlParser::StringToSimple($xml);
        }
        throw new XmlConverterException("Unknown parameter in toSimple function");
    }
}