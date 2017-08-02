<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';

class Xmlify
{
    public static function stringify($arr, $depth = 0){
        return self::recursive_stringify($arr, $depth);
    }

    protected static function recursive_stringify($arr, $depth){
        if (!is_array($arr)){
            return $arr;
        }

        $str = '';
        $tabs = str_repeat("\t", $depth);
        $attrs = self::sieveOutAttributes($arr);
        if (Utils::isStringKeyed($arr)){

        } else {

        }
        return $str;
    }

    protected static function sieveOutAttributes($arr){
        $attrs = '';
        if (array_key_exists('@attributes', $arr)){
            foreach ($arr['@attributes'] as $attr => $value){
                $attrs .= " $attr=\"$value\"";
            }
        }
        return $attrs;
    }
}