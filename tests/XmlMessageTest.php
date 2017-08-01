<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding test class to XmlMessage class
 *
 * Unit tests for XmlMessage
 */
class XmlMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XmlMessage
     *
     * Simple check for typos and such
     */
    public function testIsThereAnySyntaxError(){
        $x = new XmlMessage([]);
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Test factory pattern setup
     *
     * Assert factory pattern is working correctly
     */
    public function testFactoryPattern(){
        $x = new XmlMessage([
            'key' => 'value'
        ]);
        $this->assertTrue($x->key == 'value');
        unset($x);
    }

    /**
     * Test factory pattern fail
     *
     * Assert message is throwing exceptions if trying to access undefined property
     *
     * @expectedException Exception
     */
     public function testFactoryPatternFail(){
         $x = new XmlMessage([]);
         $x->key;
         unset($x);
     }
}