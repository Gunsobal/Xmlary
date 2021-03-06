<?php

namespace Gunsobal\Xmlary;

use \DOMDocument;
use BadMethodCallException;
use Gunsobal\Xmlary\Support;
use UnexpectedValueException;

/**
 * This class converts string keyed associative arrays into XML. It's got support for generating a DOMDocument or 
 * strings with the same markup as xml. It's got support for attributes as well as manually controlling tab
 * indents when generating a markup string. It's defensive against invalid parameters and validates
 * xml tags according to standards.
 *
 * @author Gunnar Örn Baldursson
 */
class Xmlify
{
	/**
     * Convert associative array to string
	 * 
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
	 * @throws \BadMethodCallException
	 * @throws \UnexpectedValueException
     * @return string
     */
	public static function stringify($arr, $depth = 0){
		if (Support::isStringKeyed($arr)){
			return self::recursiveStringify(key($arr), reset($arr), $depth);
		}

		throw new BadMethodCallException("Invalid arguments for stringify function, must be string keyed array");
	}

	/**
     * Convert associative array to DOMDocument
	 * 
     * @param array $arr An array to convert into XML
     * @param string $version Version head on the XML document, default 1.0
     * @param string $encoding Encoding head on the XML document, default UTF-8
	 * @throws \BadMethodCallException
	 * @throws \UnexpectedValueException
     * @return \DOMDocument
     */
    public static function xmlify($arr, $version = "1.0", $encoding = "UTF-8"){
        if (Support::isStringKeyed($arr)){
            $xml = new DOMDocument( $version, $encoding);
            $xml->preserveWhiteSpace = false;
			$xml->formatOutput = true;
			
            return self::recursiveXmlify(key($arr), reset($arr), $xml);
		}
		
        throw new BadMethodCallException("Invalid argument for xmlify function, must be string keyed array");
    }

	/**
     * Convert associative array to string with html special chars
	 * 
     * @param array $arr An array to convert into XML string
     * @param int $depth Tab indentation preceding first node, default 0
	 * @see Gunsobal\Xmlary\Xmlify::stringify()
	 * @throws \BadMethodCallException
	 * @throws \UnexpectedValueException
     * @return string
     */
    public static function htmlify($arr, $depth = 0){
       return htmlspecialchars(self::stringify($arr, $depth));
    }

	/**
     * Recursively build xml markup style string from associative array
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param int $depth
	 * @param string $attr
	 * @throws \UnexpectedValueException
	 * @return string
     */
	protected static function recursiveStringify($key, $value, $depth, $attr = ''){
		self::validateTag($key);
		
		$tabs = str_repeat("\t", $depth);
		
		if (self::isSingleElement($value)) return self::buildStringNode($key, $attr, $value, $tabs);

		$xml = ''; // Return string
		if (Support::isStringKeyed($value)){
			if (array_key_exists('@attributes', $value)) $attr = self::buildAttributesString($value['@attributes']);
			
			$insert = ''; // String of nested elements
			foreach ($value as $k => $v){ 
				if ($k !== '@attributes') $insert .= self::recursiveStringify($k, $v, $depth + 1);
			}
			
			$xml .= self::buildStringNode($key, $attr, $insert, $tabs, true);
		} else { 
			foreach ($value as $v){
				// If attributes exist, set attr string value or overwrite if it has already been set
				if (self::isAttributesArray($v)){
					$attr = self::buildAttributesString($v['@attributes']);
					continue;
				}
				
				$xml .= self::recursiveStringify($key, $v, $depth, $attr);
			}
		}

		return $xml;
	}

	/**
	 * Recursively build DOMDocument from associative array, essentially same algorithm as stringify
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param \DOMDocument $doc
	 * @param \DOMNode|null $parent
	 * @param array $attr
	 * @throws \UnexpectedValueException
	 * @return \DOMDocument
	 */
	protected static function recursiveXmlify($key, $value, $doc, $parent = null, $attr = []){
		self::validateTag($key);

		if (self::isSingleElement($value)){ // base case
			self::buildDOMNode($doc, $parent, $key, $attr, $value);
			return $doc;
		}

		if (Support::isStringKeyed($value)){
			if (array_key_exists('@attributes', $value)){
				$attr = $value['@attributes'];
				unset($value['@attributes']);
			} 

			$newparent = self::buildDOMNode($doc, $parent, $key, $attr);

			foreach ($value as $k => $v){
				self::recursiveXmlify($k, $v, $doc, $newparent);
			}
		} else {
			foreach ($value as $v){
				// Set attr or overwrite it
				if (self::isAttributesArray($v)){
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
	 * 
	 * @param \DOMDocument $doc
	 * @param \DOMDocument|\DOMNode $parent
	 * @param string $name
	 * @param array $attrs
	 * @param mixed $value
	 * @throws \UnexpectedValueException
	 * @return \DOMNode
	 */
	protected static function buildDOMNode($doc, $parent, $name, $attrs, $value = null){
		$value = self::isEmptyElement($value) ? null : Support::boolToString($value);

		$node = ($value === null ? $doc->createElement($name) : $doc->createElement($name, $value));
		
        foreach ($attrs as $a => $v){
			self::validateAttribute($a, $v);
            $v = Support::boolToString($v);
            $node->setAttribute($a, $v);
		}

		$parent === null ? $doc->appendChild($node) : $parent->appendChild($node);

        return $node;
	}

	/**
	 * Build xml node as string, line to true if value should be on a seperate line
	 * 
	 * @param string $key
	 * @param string $attrs
	 * @param mixed $value
	 * @param string $tabs
	 * @param boolean $nested
	 * @return string
	 */
	protected static function buildStringNode($key, $attrs, $value, $tabs, $nested = false){
		if (self::isEmptyElement($value)) return "$tabs<$key$attrs />\n";

		return "$tabs<$key$attrs>" . ($nested ? "\n" : '') . Support::boolToString($value) . ($nested ? $tabs : '') . "</$key>\n";
	}

	/**
	 * Convert attributes array to string if valid, else throw exception
	 * 
	 * @param array $attrs
	 * @throws \UnexpectedValueException
	 * @return string
	 */
	protected static function buildAttributesString($attrs){
		$str = '';
		foreach ($attrs as $a => $b){
			self::validateAttribute($a, $b); 
			$str .= " $a=\"" . Support::boolToString($b) . "\"";
		}
		
		return $str;
	}

	/**
     * Validate XML tag
	 * 
	 * @param string $tag
	 * @throws \UnexpectedValueException
	 * @return boolean
     */
    protected static function validateTag($tag){
		if (! preg_match('/^(?!xml.*)[a-z\_][\w\-\:\.]*$/i', $tag)) {
			throw new UnexpectedValueException("Invalid tag name: '$tag'");
		}

		return true;
    }

    /**
     * Pattern for attributes
	 * 
	 * @param string $attr
	 * @param mixed $value
	 * @throws \UnexpectedValueException
	 * @return boolean
     */
    protected static function validateAttribute($attr, $value){
		if (! preg_match('/^[a-z\_][\w\-\:\.]*$/i', $attr)){
			throw new UnexpectedValueException("Invalid attribute name: '$attr'");
		}

		if (is_array($value)){
            throw new UnexpectedValueException("Invalid attribute value for '$attr', can't be array"); 
		}

        return true;
	}
	
	/**
	 * Check if element is an empty value
	 * 
	 * @param mixed $el
	 * @return boolean
	 */
	protected static function isEmptyElement($el){
		return is_array($el) ? !count($el) : Support::isEmptyString($el);
	}

	/**
	 * Check if an array is an array of attributes only
	 * 
	 * @param mixed $a
	 * @return boolean
	 */
	protected static function isAttributesArray($a){
		return is_array($a) && array_key_exists('@attributes', $a) && count($a) === 1;
	}

	/**
	 * Check if an element is a single element, returns true for empty array.
	 * 
	 * @param mixed $el
	 * @return boolean
	 */
	protected static function isSingleElement($el){
		return !is_array($el) || !count($el);
	}
}