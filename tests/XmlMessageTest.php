<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for XmlMessage
 */
class XmlMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XmlMessage
     */
    public function testIsThereAnySyntaxError(){
        $x = new XmlMessage([]);
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Test factory pattern setup
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
     * @expectedException Exception
     */
     public function testFactoryPatternFail(){
         $x = new XmlMessage([]);
         $x->key;
         unset($x);
     }
}