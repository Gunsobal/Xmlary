<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for XsdValidator
 */
class XsdValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XsdValidator
     */
    public function testIsThereAnySyntaxError(){
        $x = new XsdValidator;
        $this->assertTrue(is_object($x));
        unset($x);
    }
}