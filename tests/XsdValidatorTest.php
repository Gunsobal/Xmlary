<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding test class to XsdValidator class
 *
 * Unit tests for XsdValidator
 */
class XsdValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in XsdValidator
     *
     * Simple check for typos and such
     */
    public function testIsThereAnySyntaxError(){
        $x = new XsdValidator;
        $this->assertTrue(is_object($x));
        unset($x);
    }
}