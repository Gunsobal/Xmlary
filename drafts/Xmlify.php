<?php

// This is a helper class which can convert a form of associative arrays to xml strings
// Use stringify if you just need the xml markup, else use xmlify_array to get a DOMDocument where you can call SaveXML() to get a proper MXL string

// Example: 
// $arr = [
//     'order' => [
//         '@attributes' => ['class' => 'order'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
//         'fruits' => [
//             [
//                 '@attributes' => ['class' => 'fruit'] // Note: class="fruit" gets applied to all elements in the fruits array unless overwritten
//             ],
//             'orange',
//             'banana',
//             [
//                 '@attributes' => ['class' => 'fruit red', 'id' => 5] //Note: Overwriting attributes
//             ],
//             'apple'
//         ],
//         'drinks' => [
//             '@attributes' => ['href' => 'myUrl'], // Note: @attributes doesn't have to be contained in its own array because 'order' is not an array of arrays
//             'drink' => [
//                 ['@attributes' => ['class' => 'red']], // Note: Attributes get applied to every subsequent drink node unless overwritten
//                 [
//                     'soda' => 'coke',
//                     'healthy' => 'water'
//                 ],
//                 ['@attributes' => []], //Note: Overwriting attributes to empty
//                 [
//                     'soda' => [
//                         ['@attributes' => [ 'color' => 'pink' ]], // Note: To add attributes to an element, the element value needs to be an array containing an array with attributes key
//                         'pepsi'
//                     ],
//                     'healthy' => 'juice'
//                 ]
//             ]
//         ]
//     ]
// ];

// Outputs:
// <order class="order">
//   <fruits class="fruit">orange</fruits>
//   <fruits class="fruit">banana</fruits>
//   <fruits class="fruit red" id="5">apple</fruits>
//   <drinks href="myUrl">
//     <drink class="red">
//       <soda>coke</soda>
//       <healthy>water</healthy>
//     </drink>
//     <drink>
//       <soda color="pink">pepsi</soda>
//       <healthy>juice</healthy>
//     </drink>
//   </drinks>
// </order>

namespace App\Helpers;

use DOMDocument;
use \Exception;

class Xmlify
{
    /**
     * Converts an array to an XML DOMDocument with keys as nodes
     * @param Array $arr - Array to convert to xml, nodes will be named after associative keys
     * @param String $root - name of root node, can't be empty
     * @return DOMDocument
     * */
    public static function xmlify_array($arr, $version = "1.0", $encoding = "UTF-8"){
        if (is_array($arr) && self::isAssoc($arr)){
            $xml = new DOMDocument( $version, $encoding );
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            return self::recursive_xmlify($arr, $xml);
        }else{
            throw new Exception("[xmlify] Invalid argument for xmlify_array function, must be a non-empty associative array");
        }
    }

    /**
     * Converts an array to an xml-ish format string with keys as nodes
     * @param Array $arr - Array to convert to xml-ish string, nodes will be named after associative keys
     * @param String $root - name of root node if any
     * @param Int $depth - How many tab indents to add before first node, default 0
     * @return String
     * */
    public static function stringify($arr, $depth = 0){
        if (is_array($arr) && self::isAssoc($arr)){
            return self::recursive_stringify($arr, $depth);
        }else{
            throw new Exception("[xmlify] Invalid argument for stringify function, must be a non-empty associative array");
        }
    }

    /**
     * Converts an array to an xml-ish format string with html entitites, keys are nodes
     * @param Array $arr - Array to convert to xml-ish string, nodes will be named after associative keys
     * @param String $root - name of root node if any
     * @param Int $depth - How many tab indents to add before first node, default 0
     * @return String
     * */
    public static function stringify_html($arr, $depth = 0){
       return htmlspecialchars(self::stringify($arr, $depth));
    }

    /**
     * Recursively build xml-ish string
     * @param Array $arr - Array to stringify
     * @param Int $depth - Level of nesting, controls tab indentation
     * @return String
     * */
    protected static function recursive_stringify($arr, $depth){
        $str = ''; 
        foreach ($arr as $key => $value){
            if (self::validTag($key)){ // Validate xml tag
                if (is_array($value)){ // Start array handling
                    $attr = '';
                    if (self::isAssoc($value)){ // Handle associative array
                        if (array_key_exists('@attributes', $value)){ 
                            foreach ($value['@attributes'] as $a_key => $a_val){
                                if (self::validAttr($a_key)){
                                    $attr .= " $a_key=\"$a_val\"";
                                }else{
                                    throw new Exception("[xmlify] Invalid attribute tag: \"$a_key\" in stringify function");
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
                                            throw new Exception("[xmlify] Invalid attribute tag: \"$a_key\" in stringify function");
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
                throw new Exception("[xmlify] Invalid xml tag name: \"$key\" for stringify function");
            }
        }
        return $str;
    }

    /**
     * Recursively build xml DOMDocument
     * @param Array $arr - Array to xmlify
     * @param DOMDocument $xml - DOMDocument being built
     * @param DOMNode $node - Recursively set DOMNode parent
     * @return DOMDocument
     * */
    protected static function recursive_xmlify($arr, $xml, $node = null){
        foreach ($arr as $key=>$value){
            if (self::validTag($key)){ // Validate xml tag
                if (is_array($value)){ // start array handling
                    if (self::isAssoc($value)){ // handle associative array
                        $xml_node = $xml->createElement($key);
                        if (array_key_exists('@attributes', $value)){ // handle attributes
                            foreach ($value['@attributes'] as $a_key => $a_val){
                                if (self::validAttr($a_key)){
                                    $xml_node->setAttribute($a_key, $a_val);
                                }else{
                                    throw new Exception("[xmlify] Invalid attribute tag: \"$a_key\" in xmlify_array function");
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
                                            throw new Exception("[xmlify] Invalid attribute tag: \"$a_key\" in xmlify_array function");
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
                throw new Exception("[xmlify] Invalid xml tag name: \"$key\" for xmlify_array function");
            }
        }
        return $xml;
    }

    /**
     * Validates a string to see if it's a legal xml tag
     * @param String $tag - Tag name to be validated as valid xml
     * @return Boolean
     * */
    protected static function validTag($tag){
        return preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag);
    }

    /**
     * Validates a string to see if it's a legal xml attribute
     * @param String $attr - Attribute name to be validated as valid xml
     * @return Boolean
     * */
    protected static function validAttr($attr){
        return preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr);
    }

    /**
     * Checks if an array is an associative array
     * @param Array $arr
     * @return Boolean
     * */
    protected static function isAssoc($arr){
        return (bool)count(array_filter(array_keys($arr), 'is_string'));
    }
}