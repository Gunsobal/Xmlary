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
    static public function class_basename($class){
        $names = explode('\\', get_class($class));
        return $names[count($names) - 1];
    }
}