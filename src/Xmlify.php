<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';

class Xmlify
{
    public static function stringify($arr, $depth = 0){
        return self::recursiveStringify($arr, $depth);
    }

    public static function xmlify($arr, $version = "1.0", $encoding = "UTF-8"){
        $xml = new DOMDocument( $version, $encoding);
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        return self::recursiveXmlify($arr, $xml);
    }

    protected static function recursiveStringify($arr, $depth){
        $str = '';
        $tabs = str_repeat("\t", $depth);
        $attrs = '';
        foreach ($arr as $key => $value){
            if ($key === '@attributes') continue; // Ignore stringifying for attributes, it's handled differently
            $attrs = self::stringifyAttributes($value); // Get attributes for this node
            
            if (Utils::isStringKeyed($value)){ // If content of this node is string keyed, create node and go level deeper
                $str .= "$tabs<$key$attrs>\n" . self::recursiveStringify($value, $depth  + 1) . "$tabs</$key>\n";
            } else {
                if (is_array($value)){ // If content is arrayed but not string keyed, multiple nodes with same name
                    foreach ($value as $v){
                        if (!is_array($v)){ // If content within multiple node is not complex
                            $str .= $tabs . self::stringifyXmlNode($key, $attrs, $v);
                        } else { // If content within mulitple node is complex
                            if (count($v) === 1 && array_key_exists('@attributes', $v)) continue;  //Don't print extra nodes for attribute value
                            $attrs = self::stringifyAttributes($v); // Get attributes specific to this node
                            $str .= "$tabs<$key$attrs>\n" . self::recursiveStringify($v, $depth + 1) . "$tabs</$key>\n"; // Go 1 level deeper
                        }
                    }
                } else {
                    $str .= $tabs . self::stringifyXmlNode($key, $attrs, $value);
                }
            }
        }
        return $str;
    }

    protected static function recursiveXmlify($arr, $xml){
        foreach ($arr as $key => $value){
            $attrs = self::stringifyAttributes($value);
            if ($key === '@attributes') continue;

            $node = $xml->createElement($key);
            if (Utils::isStringKeyed($value)){

            }
        }
        return $xml;
    }

    protected static function stringifyXmlNode($key, $attrs, $value){
        if (Utils::isEmptyString($value)){
            return "<$key$attrs />\n";
        } else {
            return "<$key$attrs>$value</$key>\n";
        }
    }

    protected static function stringifyAttributes($arr){
        $attrs = '';
        if (!is_array($arr)) return $attrs;
        if (array_key_exists('@attributes', $arr)){ // Get attributes when it's keyed in with other keys
            foreach ($arr['@attributes'] as $attr => $value){
                $attrs .= " $attr=\"$value\"";
            }
        } else {
            foreach ($arr as $v){ // Extract attributes from array
                if (is_array($v) && array_key_exists('@attributes', $v)){
                    foreach ($v['@attributes'] as $attr => $value){
                        $attrs .= " $attr=\"$value\"";
                    }
                }
            }
        }
        return $attrs;
    }
}