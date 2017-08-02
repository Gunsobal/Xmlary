<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';
include_once 'XmlaryException.php';

use \DOMDocument;

class Xmlify
{
    public static function stringify($arr, $depth = 0){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            return self::recursiveStringify($arr, $depth);
        }
        throw new XmlifyException("Invalid argument for stringify function");
    }

    public static function xmlify($arr, $version = "1.0", $encoding = "UTF-8"){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            $xml = new DOMDocument( $version, $encoding);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            return self::recursiveXmlify($arr, $xml);
        }
        throw new XmlifyException("Invalid argument for xmlify function");
    }

    protected static function recursiveStringify($arr, $depth){
        $str = '';
        $tabs = str_repeat("\t", $depth);
        $attrs = '';
        foreach ($arr as $key => $value){
            if ($key === '@attributes') continue; // Ignore stringifying for attributes, it's handled differently
            $attrArray = self::sieveOutAttributes($value);
            self::validateElement($key, $attrArray);

            $attrs = self::stringifyAttributes($attrArray); // Get attributes for this node
            
            if (Utils::isStringKeyed($value)){ // If content of this node is string keyed, create node and go level deeper
                $str .= "$tabs<$key$attrs>\n" . self::recursiveStringify($value, $depth  + 1) . "$tabs</$key>\n";
            } else {
                if (is_array($value)){ // If content is arrayed but not string keyed, multiple nodes with same name
                    foreach ($value as $v){
                        if (!is_array($v)){ // If content within multiple node is not complex
                            $str .= $tabs . self::stringifyXmlNode($key, $attrs, $v);
                        } else { // If content within mulitple node is complex
                            if (count($v) === 1 && array_key_exists('@attributes', $v)) continue;  //Don't print extra nodes for attribute value
                            $attrArray = self::sieveOutAttributes($v);
                            $attrs = self::stringifyAttributes($attrArray); // Get attributes specific to this node
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

    protected static function recursiveXmlify($arr, $xml, $node = null){
        foreach ($arr as $key => $value){
            if ($key === '@attributes') continue;
            $attrs = self::sieveOutAttributes($value);
            self::validateElement($key, $attrs);
            
            if (Utils::isStringKeyed($value)){
                $node = self::buildDOMNode($xml, $node, $key, $attrs);
                self::recursiveXmlify($value, $xml, $node);
            } else {
                if (is_array($value)){
                    foreach ($value as $v){
                        if (!is_array($v)){
                            self::buildDOMNode($xml, $node, $key, $attrs, $v);
                        } else {
                            if (count($v) === 1 && \array_key_exists('@attributes', $v)) continue;
                            $attrs = self::sieveOutAttributes($v);
                            $n = self::buildDOMnode($xml, $node, $key, $attrs);
                            self::recursiveXmlify($v, $xml, $n);
                        }
                    }
                } else {
                    self::buildDOMNode($xml, $node, $key, $attrs, $value);
                }
            }
        }
        return $xml;
    }

    protected static function validateElement($key, $attrs){
        if (!self::validTag($key)){
            throw new XmlifyException("Invalid tag name: '$key'");
        }
        foreach ($attrs as $attr => $val){
            if (!self::validAttr($attr)){
                throw new XmlifyException("Invalid attribute name: '$attr'");
            }
        }
    }

    protected static function sieveOutAttributes($arr){
        if (!is_array($arr)) return [];
        if (array_key_exists('@attributes', $arr)){ // Get this level attributes
            return $arr['@attributes'];
        } else {
            foreach ($arr as $v){
                if (is_array($v) && array_key_exists('@attributes', $v)){ // Get 1 level deeper attributes
                    return $v['@attributes'];
                }
            }
        }
        return [];
    }

    protected static function buildDOMNode($document, $parent, $name, $attrs, $value = null){
        $node = ($value == null ? $document->createElement($name) : $document->createElement($name, $value));
        foreach ($attrs as $a => $v){
            $node->setAttribute($a, $v);
        }
        if ($parent === null) {
            $document->appendChild($node);
        } else {
            $parent->appendChild($node);
        }
        return $node;
    }

    protected static function stringifyXmlNode($key, $attrs, $value){
        if (Utils::isEmptyString($value)){
            return "<$key$attrs/>\n";
        } else {
            return "<$key$attrs>$value</$key>\n";
        }
    }

    protected static function stringifyAttributes($arr){
        $attrs = '';
        if (!is_array($arr)) return $attrs;
        foreach ($arr as $attr => $value){
            $attrs .= " $attr=\"$value\"";
        }
        return $attrs;
    }

    protected static function validTag($tag){
        return preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag);
    }

    protected static function validAttr($attr){
        return preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr);
    }
}