<?php

namespace Gunsobal\Xmlary;

/**
 * Utilities class
 *
 * @author Gunnar Baldursson
 */ 
class Utils
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
}