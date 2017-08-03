<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';
include_once 'XmlaryException.php';

use \DOMDocument;

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
     * Convert associative array to string
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
     * @return string
     */
    public static function stringify($arr, $depth = 0){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            return self::recursiveStringify($arr, $depth);
        }
        throw new XmlifyException("Invalid argument for stringify function");
    }

    /**
     * Convert associative array to DOMDocument
     * @param array $arr An array to convert into XML
     * @param string $version Version head on the XML document, default 1.0
     * @param string $encoding Encoding head on the XML document, default UTF-8
     * @return \DOMDocument
     */
    public static function xmlify($arr, $version = "1.0", $encoding = "UTF-8"){
        if (is_array($arr) && Utils::isStringKeyed($arr)){
            $xml = new DOMDocument( $version, $encoding);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            return self::recursiveXmlify($arr, $xml);
        }
        throw new XmlifyException("Invalid argument for xmlify function");
    }

    /**
     * Convert associative array to string with html special chars
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
     * @return string
     */
    public static function htmlify($arr, $depth = 0){
       return htmlspecialchars(self::stringify($arr, $depth));
    }

    /**
     * Recursively build xml markup style string from associative array
     */
    protected static function recursiveStringify($arr, $depth){
        $str = '';
        $tabs = str_repeat("\t", $depth);
        foreach ($arr as $key => $value){
            if ($key === '@attributes') continue; // Ignore stringifying for control keys
            self::validateElement($key);
            $attrArray = self::getAttributes($value);
            self::validateAttributes($attrArray);
            $attrs = self::stringifyAttributes($attrArray); // Get attributes for this node
            
            if (Utils::isStringKeyed($value)){ // If content of this node is string keyed, create node and go level deeper
                if (count($value) === 0 || self::isAttributes($value)){ // Enable empty tag with attributes
                    $str .= "$tabs<$key$attrs/>\n";
                } else {
                    $str .= "$tabs<$key$attrs>\n" . self::recursiveStringify($value, $depth  + 1) . "$tabs</$key>\n";
                }
            } else {
                if (is_array($value)){ // If content is arrayed but not string keyed, multiple nodes with same name
                    foreach ($value as $v){
                        if (!is_array($v)){ // If content within multiple node is not complex
                            $attrs = self::stringifyAttributes($attrArray);
                            $str .= $tabs . self::stringifyXmlNode($key, $attrs, $v);
                        } else { // If content within mulitple node is complex
                            if (self::isAttributes($v)) {
                                $attrArray = self::getAttributes($v); // Overwrite attributes if a later attribute array is found
                                self::validateAttributes($attrArray);
                                if (array_key_exists('@@empty', $attrArray)){
                                    $str .= $tabs . self::stringifyXmlNode($key, self::stringifyAttributes($attrArray), '');
                                }
                                continue;  //Don't print extra nodes for attribute value unless next value is attributes
                            }
                            $attrArray = self::getAttributes($v);
                            self::validateAttributes($attrArray);
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

    /**
     * Recursively build DOMDocument from associative array, same structure as stringify
     */
    protected static function recursiveXmlify($arr, $xml, $node = null){
        foreach ($arr as $key => $value){
            if ($key === '@attributes') continue;
            self::validateElement($key);
            $attrs = self::getAttributes($value);
            self::validateAttributes($attrs);
            
            if (Utils::isStringKeyed($value)){
                if (self::isAttributes($value)){
                    self::buildDOMNode($xml, $node, $key, $attrs);
                } else {
                    $node = self::buildDOMNode($xml, $node, $key, $attrs);
                    self::recursiveXmlify($value, $xml, $node);
                }
            } else {
                if (is_array($value)){
                    foreach ($value as $v){
                        if (!is_array($v)){
                            self::buildDOMNode($xml, $node, $key, $attrs, $v);
                        } else {
                            if (self::isAttributes($v)){
                                $attrs = self::getAttributes($v);
                                self::validateAttributes($attrs);
                                if (array_key_exists('@@empty', $attrs)){
                                    self::buildDOMNode($xml, $node, $key, $attrs);
                                }
                                continue;
                            } 
                            $attrs = self::getAttributes($v);
                            self::validateAttributes($attrs);
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

    /**
     * Validates XML element according to standards on w3
     */
    protected static function validateElement($key){
        if (!self::validTag($key)){
            throw new XmlifyException("Invalid tag name: '$key'");
        }
    }

    /**
     * Validate attributes array
     */
    protected static function validateAttributes($attr){
        foreach ($attr as $a => $v){
            if (!self::validAttr($a)){
                throw new XmlifyException("Invalid attribute name: '$a'");
            }
        }
    }

    /** 
     * Find attributes or none for a node
     */
    protected static function getAttributes($arr){
        if (!is_array($arr)) return [];
        if (array_key_exists('@attributes', $arr)){ // Get this level attributes
            return $arr['@attributes'];
        } 
        return [];
    }

    /**
     * Build a DOMNode attached to a parent
     */
    protected static function buildDOMNode($document, $parent, $name, $attrs, $value = null){
        $node = ($value == null ? $document->createElement($name) : $document->createElement($name, $value));
        foreach ($attrs as $a => $v){
            if (!self::isControlAttribute($a)){
                $node->setAttribute($a, $v);
            }
        }
        if ($parent === null) {
            $document->appendChild($node);
        } else {
            $parent->appendChild($node);
        }
        return $node;
    }

    /**
     * Build an xml node in string
     */
    protected static function stringifyXmlNode($key, $attrs, $value){
        if (Utils::isEmptyString($value)){
            return "<$key$attrs/>\n";
        } else {
            return "<$key$attrs>$value</$key>\n";
        }
    }

    /**
     * Build attributes string
     */
    protected static function stringifyAttributes($arr){
        $attrs = '';
        if (!is_array($arr)) return $attrs;
        foreach ($arr as $attr => $value){
            if (! self::isControlAttribute($attr)){
                $attrs .= " $attr=\"$value\"";
            }
        }
        return $attrs;
    }

    protected static function isAttributes($arr){
        return array_key_exists('@attributes', $arr) && count($arr) === 1;
    }

    protected static function isControlAttribute($attr){
        return preg_match('/^@@.*$/', $attr);
    }

    /**
     * Validate XML tag
     */
    protected static function validTag($tag){
        return preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag);
    }

    /**
     * Validate attribute
     */
    protected static function validAttr($attr){
        return self::isControlAttribute($attr) || preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr);
    }
}