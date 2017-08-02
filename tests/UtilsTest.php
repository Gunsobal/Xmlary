<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for Utils
 */
class UtilsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in Utils
     */
    public function testIsThereAnySyntaxError(){
        $x = new Utils;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Testing class_basename
     */
    public function testClassBasename(){
        $this->assertTrue(Utils::getClassBasename($this) == 'UtilsTest');
        $this->assertFalse(Utils::getClassBasename(new Utils) == 'UtilsTest');
    }

    /**
     * Testing isStringKeyed
     */
    public function testIsStringKeyed(){
        $this->assertTrue(Utils::isStringKeyed(['key' => 'value']));
        $this->assertFalse(Utils::isStringKeyed([0 => '1']));
        $this->assertFalse(Utils::isStringKeyed(['a', 'b']));
    }

    /**
     * Testing isJson
     */
    public function testIsJson(){
        $this->assertTrue(Utils::isJson('{"myjson":"string"}'));
        $this->assertFalse(Utils::isJson('abc'));
        $this->assertFalse(Utils::isJson(0));
        $this->assertFalse(Utils::isJson('0'));
    }
}