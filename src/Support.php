<?php

namespace Gunsobal\Xmlary;

class Support
{
	/**
    * Utility function to check if an array is string keyed
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
    * Utility function to get a class' basename
    * @param \stdClass $class Class object
    * @return string 
    */
    static public function getClassBasename($class){
        $names = explode('\\', get_class($class));
        return $names[count($names) - 1];
	}
	
	/**
    * Utility function to check for empty string
    * @param string $str
    * @return boolean
    */
    static public function isEmptyString($str){
        return ($str === null || $str === '');
    }

    /**
    * Utility function to check for json string
    * @param string $str
    * @return boolean
    */
    static public function isJson($str){
        if (is_numeric($str) || !is_string($str)) return false;
        json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Utility function to convert bool to string
     * @param mixed $b - variable to convert from bool to string
     * @param boolean $num - True to get numeric values from bool, false for strings
     * @return mixed - Returns $b unless it's a bool, then it returns a string
     */
    static public function boolToString($b, $num = true){
        return (is_bool($b) ? ($num ? ($b ? '1' : '0') : ($b ? 'true' : 'false')) : $b);
    }

    /**
     * Utility function to substring to first occurrence of a substring in a string if any such substring exists
     * @param string $str - String to cut
     * @param string $s1 - Target substring
     * @return string
     */
    static public function SubstrToFirst($str, $s1){
        $i = strpos($str, $s1);
		if ($i !== false) return substr($str, 0, $i);
		return $str;
    }

    /**
     * Utility function to substring to last occurrence of a substring in a string if any such substring exists
     * @param string $str - String to cut
     * @param string $s1 - Target substring
     * @return string
     */
    static public function SubstrToLast($str, $s1){
        $i = strrpos($str, $s1);
		if ($i !== false) return substr($str, 0, $i);
		return $str;
	}
	
	/**
	 * Utility function to compare XML strings
	 * @param string $s1
	 * @param string $s2
	 * @return boolean
	 */
	static public function CompareXMLStrings($s1, $s2){
		$strip = '/\s+/';
		$c1 = preg_replace($strip, '', simplexml_load_string(trim($s1))->asXML());
		$c2 = preg_replace($strip, '', simplexml_load_string(trim($s2))->asXML());
		return $c1 == $c2;
	}
}