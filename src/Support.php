<?php

namespace Gunsobal\Xmlary;

use InvalidArgumentException;

/**
 * Gunsobal\Xmlary\Support is a collection of utility public static functions.
 *
 * @author Gunnar Örn Baldursson
 */
class Support
{
	/**
     * Check if an array is string keyed
     * 
     * @param array $arr Array to check for string keys
     * @return boolean
     */
    static public function isStringKeyed($arr){
        if (!is_array($arr)) return false;
        foreach ($arr as $key => $value){
            if (!is_string($key)){
                return false;
            }
        }
        return true;
	}
	
	/**
     * Get a class' basename
     * 
     * @param object $class Class object
     * @throws \InvalidArgumentException
     * @return string
     */
    static public function getClassBasename($class){
        if (!is_object($class)) throw new InvalidArgumentException("$class must be an object");
        $names = explode('\\', get_class($class));
        return end($names);
	}
	
	/**
     * Check for empty string
     * 
     * @param string $str
     * @return boolean
     */
    static public function isEmptyString($str){
        return ($str === null || $str === '');
    }

    /**
     * Convert bool to string
     * 
     * @param mixed $b - variable to convert from bool to string
     * @param boolean $num - True to get numeric values from bool, false for strings
     * @return mixed Returns $b unless it's a bool, then it returns a string
     */
    static public function boolToString($b, $num = true){
        return (is_bool($b) ? ($num ? ($b ? '1' : '0') : ($b ? 'true' : 'false')) : $b);
    }
	
	/**
	 * Compare XML strings
     * 
	 * @param string $s1
	 * @param string $s2
	 * @return boolean
	 */
	static public function compareXMLStrings($s1, $s2){
        $strip = '/\s+/';
		$c1 = preg_replace($strip, '', simplexml_load_string(trim($s1))->asXML());
		$c2 = preg_replace($strip, '', simplexml_load_string(trim($s2))->asXML());
		return $c1 == $c2;
	}
}