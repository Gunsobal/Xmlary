<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding test class to Utils class
 *
 * Unit tests for Utils
 */
class UtilsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in Utils
     *
     * Simple check for typos and such
     */
    public function testIsThereAnySyntaxError(){
        $x = new Utils;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Testing class_basename
     *
     * Assert function works as intended
     */
    public function testClassBasename(){
        $this->assertTrue(Utils::class_basename($this) == 'UtilsTest');
        $this->assertFalse(Utils::class_basename(new Utils) == 'UtilsTest');
    }

    /**
     * Testing isStringKeyed
     *
     * Assert function works as intended
     */
    public function testIsStringKeyed(){
        $this->assertTrue(Utils::isStringKeyed(['key' => 'value']));
        $this->assertFalse(Utils::isStringKeyed([0 => '1']));
        $this->assertFalse(Utils::isStringKeyed(['a', 'b']));
    }
}