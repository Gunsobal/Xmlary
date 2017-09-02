<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';

use Gunsobal\Utils\Strings;
use Gunsobal\Utils\Arrays;

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
		if (Arrays::isStringKeyed($arr)){
			return self::recursiveStringify(key($arr), reset($arr), $depth);
		} else {
			throw new XmlifyException("Invalid arguments for stringiy function, must be string keyed array");
		}
	}

	/**
     * Convert associative array to DOMDocument
     * @param array $arr An array to convert into XML
     * @param string $version Version head on the XML document, default 1.0
     * @param string $encoding Encoding head on the XML document, default UTF-8
     * @return \DOMDocument
     */
    public static function xmlify($arr, $version = "1.0", $encoding = "UTF-8"){
        if (Arrays::isStringKeyed($arr)){
            $xml = new DOMDocument( $version, $encoding);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;
            return self::recursiveXmlify(key($arr), reset($arr), $xml);
        }
        throw new XmlifyException("Invalid argument for xmlify function, must be string keyed array");
    }

	/**
     * Convert associative array to string with html special chars
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
     * @return string
     */
    public static function htmlify($arr, $formatted = true, $depth = 0){
       return htmlspecialchars(self::stringify($arr, $depth));
    }

	/**
     * Recursively build xml markup style string from associative array
     */
	protected static function recursiveStringify($key, $value, $depth, $attr = ''){
		self::validateTag($key);
		$tabs = str_repeat("\t", $depth); // Create tab indentation
		if (!is_array($value) || !count($value)){ // Base case, create <key>value</key> with attributes if any
			return self::isEmptyElement($value) ? "$tabs<$key$attr />\n" : "$tabs<$key$attr>" . Strings::boolToString($value) . "</$key>\n";
		}
		$xml = ''; // Return string
		if (Arrays::isStringKeyed($value)){ // Handle nested associative array
			if (array_key_exists('@attributes', $value)) $attr = self::stringifyAttributes($value['@attributes']); // Set attributes string if any attributes are defined
			$insert = ''; // String of nested elements
			foreach ($value as $k => $v){ // Build nested element string
				if ($k !== '@attributes') $insert .= self::recursiveStringify($k, $v, $depth + 1);
			}
			$xml .= self::isEmptyElement($insert) ? "$tabs<$key$attr />\n" : "$tabs<$key$attr>\n$insert$tabs</$key>\n"; // Build <key>longstring</key>
		} else { // Handle value is array but not keyed, multiple keys with same name
			foreach ($value as $v){
				if (is_array($v) && array_key_exists('@attributes', $v) && count($v) === 1){ // If attributes exist, set attr string value or overwrite if it has already been set
					$attr = self::stringifyAttributes($v['@attributes']);
					continue;
				}
				$xml .= self::recursiveStringify($key, $v, $depth, $attr);
			}
		}
		return $xml;
	}

	/**
	 * Recursively build DOMDocument from associative array, follows same flow as stringifyRecursive
	 */
	protected static function recursiveXmlify($key, $value, $doc, $parent = null, $attr = []){
		if (!is_array($value) || !count($value)){ // base case
			self::buildDOMNode($doc, $parent, $key, $attr, $value);
			return $doc;
		}
		if (Arrays::isStringKeyed($value)){ // handle assoc array
			if (array_key_exists('@attributes', $value)){
				$attr = $value['@attributes'];
				unset($value['@attributes']); // Get attributes key, then delete it
			} 
			$newparent = self::buildDOMNode($doc, $parent, $key, $attr);
			foreach ($value as $k => $v){
				self::recursiveXmlify($k, $v, $doc, $newparent);
			}
		} else {
			foreach ($value as $v){
				if (is_array($v) && array_key_exists('@attributes', $v) && count($v) === 1){ // Set attr or overwrite it
					$attr = $v['@attributes'];
					continue;
				}
				self::recursiveXmlify($key, $v, $doc, $parent, $attr);
			}
		}

		return $doc;
	}

	/**
	 * Build DOM node
	 */
	protected static function buildDOMNode($doc, $parent, $name, $attrs, $value = null){
		$value = self::isEmptyElement($value) ? null : Strings::boolToString($value);
        $node = ($value === null ? $doc->createElement($name) : $doc->createElement($name, $value));
        foreach ($attrs as $a => $v){
            $v = Strings::boolToString($v);
            $node->setAttribute($a, $v);
        }
        if ($parent === null)   $doc->appendChild($node);
        else 					$parent->appendChild($node);
        return $node;
	}


	/**
	 * Check if element is an empty value
	 */
	protected static function isEmptyElement($el){
		return is_array($el) ? !count($el) : Strings::isEmptyString($el);
	}

	/**
	 * Convert attributes array to string if valid, else throw exception
	 */
	protected static function stringifyAttributes($attrs){
		$str = '';
		foreach ($attrs as $a => $b){
			self::validateAttribute($a, $b); 
			$str .= " $a=\"$b\"";
		}
		return $str;
	}

	/**
     * Validate XML tag
     */
    protected static function validateTag($tag){
		if (! preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag)) {
			throw new XmlifyException("Invalid tag name: '$tag'");
		}
		return true;
    }

    /**
     * Pattern for attributes
     */
    protected static function validateAttribute($attr, $value){
		if (! preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr)){
			throw new XmlifyException("Invalid attribute name: '$attr'");
		}
		if (is_array($value)){
            throw new XmlifyException("Invalid attribute value for '$attr', can't be array"); 
		}
        return true;
    }
}