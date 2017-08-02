<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';
include_once 'Utils.php';

use DOMDocument;

/**
 * This class converts string keyed associative arrays into XML. It's got support for generating a DOMDocument or 
 * strings with the same markup as xml. It's got support for attributes as well as manually controlling tab
 * indents when generating a markup string. It's defensive against invalid parameters and validates
 * xml tags according to standards.
 *
 * @author Gunnar Baldursson
 */
class Xmlify
{
    /**
     * Convert associative array to DOMDocument
     * @param array $arr An array to convert into XML
     * @param string $version Version head on the XML document, default 1.0
     * @param string $encoding Encoding head on the XML document, default UTF-8
     * @return \DOMDocument
     */
    public static function xmlify_array($arr, $version = "1.0", $encoding = "UTF-8"){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            $xml = new DOMDocument( $version, $encoding );
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            return self::recursive_xmlify($arr, $xml);
        }else{
            throw new XmlifyException("Invalid argument for xmlify_array function, param 0 must be a string keyed array");
        }
    }
    
    /**
     * Convert associative array to string
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
     * @return string
     */
    public static function stringify($arr, $depth = 0){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            return self::recursive_stringify($arr, $depth);
        } else {
            throw new XmlifyException("Invalid argument for stringify function, param 0 must be a string keyed array");
        }
    }

    /**
     * Convert associative array to string with html special chars
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
     * @return string
     */
    public static function stringify_html($arr, $depth = 0){
       return htmlspecialchars(self::stringify($arr, $depth));
    }

    protected static function recursive_xmlify($arr, $xml, $node = null){
        foreach ($arr as $key=>$value){
            if (self::validTag($key)){ // Validate xml tag
                if (is_array($value)){ // start array handling
                    if (Utils::isStringKeyed($value)){ // handle associative array
                        $xml_node = $xml->createElement($key);
                        if (array_key_exists('@attributes', $value)){ // handle attributes
                            foreach ($value['@attributes'] as $a_key => $a_val){
                                if (self::validAttr($a_key)){
                                    $xml_node->setAttribute($a_key, $a_val);
                                }else{
                                    throw new XmlifyException("Invalid attribute tag: \"$a_key\" in xmlify_array function");
                                }
                            }
                            unset($value['@attributes']);
                        }
                        ($node === null ? $xml->appendChild($xml_node) : $node->appendChild($xml_node)); 
                        self::recursive_xmlify($value, $xml, $xml_node);
                    }else{ // handle regular array
                        $attrs = [];
                        foreach ($value as $val){
                            if (is_array($val)){ // handle nested  array
                                if (array_key_exists('@attributes', $val)){ // handle attributes
                                $attrs = [];
                                    foreach ($val['@attributes']as $a_key => $a_val){
                                        if (self::validAttr($a_key)){
                                            $attrs[$a_key] = $a_val;
                                        }else{
                                            throw new XmlifyException("Invalid attribute tag: \"$a_key\" in xmlify_array function");
                                        }
                                    }
                                    continue;
                                }else{ 
                                    $xml_node = $xml->createElement($key);
                                    foreach ($attrs as $a_key => $a_val){
                                        $xml_node->setAttribute($a_key, $a_val);
                                    }
                                    ($node === null ? $xml->appendChild($xml_node) : $node->appendChild($xml_node)); 
                                    self::recursive_xmlify($val, $xml, $xml_node);
                                }
                            }else{ // handle no nested array
                                $xml_node = $xml->createElement($key, $val);
                                foreach ($attrs as $a_key => $a_val){
                                    $xml_node->setAttribute($a_key, $a_val);
                                }
                                ($node === null ? $xml->appendChild($xml_node) : $node->appendChild($xml_node)); 
                            }
                        }
                    }
                }else{ // handle regular element, no array
                    $xml_node = $xml->createElement($key, $value);
                    ($node === null ? $xml->appendChild($xml_node) : $node->appendChild($xml_node)); 
                }
            }else{
                throw new XmlifyException("Invalid xml tag name: \"$key\" for xmlify_array function");
            }
        }
        return $xml;
    }

    protected static function recursive_stringify($arr, $depth){
        $str = ''; 
        foreach ($arr as $key => $value){
            if (self::validTag($key)){ // Validate xml tag
                if (is_array($value)){ // Start array handling
                    $attr = '';
                    if (Utils::isStringKeyed($value)){ // Handle associative array
                        if (array_key_exists('@attributes', $value)){ 
                            foreach ($value['@attributes'] as $a_key => $a_val){
                                if (self::validAttr($a_key)){
                                    $attr .= " $a_key=\"$a_val\"";
                                }else{
                                    throw new XmlifyException("Invalid attribute tag: \"$a_key\" in stringify function");
                                }
                            }
                            unset($value['@attributes']);
                        } 
                        $str .= str_repeat("\t", $depth) . "<$key$attr>\n" . self::recursive_stringify($value, $depth + 1) . str_repeat("\t", $depth) . "</$key>\n";
                    }else{ // Handle regular array
                        foreach ($value as $val){
                            if (is_array($val)){ // Check for nested arrays
                                if (array_key_exists('@attributes', $val)){
                                    $attr = '';
                                    foreach ($val['@attributes'] as $a_key => $a_val){
                                        if (self::validAttr($a_key)){
                                            $attr .= " $a_key=\"$a_val\"";
                                        }else{
                                            throw new XmlifyException("Invalid attribute tag: \"$a_key\" in stringify function");
                                        }
                                    }
                                    if (count($value) == 1){ // Allow attributes on empty tags
                                        $str .= str_repeat("\t", $depth) . "<$key$attr />\n";
                                    }
                                    continue;
                                }
                                $str .= str_repeat("\t", $depth) . "<$key$attr>\n" . self::recursive_stringify($val, $depth + 1) . str_repeat("\t", $depth) . "</$key>\n";
                            }else{ // No nested array
                                $str .= str_repeat("\t", $depth) . "<$key$attr>$val</$key>\n";
                            }
                        }
                    }
                }else{ // Handle not array, no attributes
                    if (strlen($value) > 0){ // Allow empty tags
                        $str .= str_repeat("\t", $depth) . "<$key>$value</$key>\n";
                    }else{
                        $str .= str_repeat("\t", $depth) . "<$key />\n";
                    }
                }
            }else{ // Invalid xml tag
                throw new XmlifyException("Invalid xml tag name: \"$key\" for stringify function");
            }
        }
        return $str;
    }

    protected static function validTag($tag){
        return preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag);
    }

    protected static function validAttr($attr){
        return preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr);
    }
}