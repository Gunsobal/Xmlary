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
        $this->assertTrue(Utils::class_basename($this) == 'UtilsTest');
        $this->assertFalse(Utils::class_basename(new Utils) == 'UtilsTest');
    }

    /**
     * Testing isStringKeyed
     */
    public function testIsStringKeyed(){
        $this->assertTrue(Utils::isStringKeyed(['key' => 'value']));
        $this->assertFalse(Utils::isStringKeyed([0 => '1']));
        $this->assertFalse(Utils::isStringKeyed(['a', 'b']));
    }
}