<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

class SupportTest extends PHPUnit_Framework_TestCase
{
	/**
     * Checking for syntax error in Support
     */
    public function testIsThereAnySyntaxError(){
        $x = new Support;
        $this->assertTrue(is_object($x));
        unset($x);
	}
	
	public function testIsStringKeyed(){
		$this->assertTrue(Support::isStringKeyed([]));
		$this->assertTrue(Support::isStringKeyed(['a' => 'b']));
		$this->assertFalse(Support::isStringKeyed(['a','b']));
		$this->assertFalse(Support::isStringKeyed(['a']));
		$this->assertFalse(Support::isStringKeyed(0));
	}

	public function testGetClassBasename(){
		$this->assertTrue(Support::getClassBasename($this) == 'SupportTest');
		$this->assertTrue(Support::getClassBasename(new Support) == 'Support');
	}

	/**
	 * @expectedException Gunsobal\Xmlary\XmlaryException
	 */
	public function testGetClassBasenameFail(){
		Support::getClassBasename(0);
	}
}