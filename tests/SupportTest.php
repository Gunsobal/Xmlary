<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for Support
 */
class SupportTest extends PHPUnit_Framework_TestCase
{
    public function testIsThereAnySyntaxError(){
        $x = new Support;
        $this->assertTrue(is_object($x));
        unset($x);
	}
	
	public function testIsStringKeyedIsFalseForNonArrays(){
		$this->assertFalse(Support::isStringKeyed(0));
	}
	
	public function testStringKeyedIsFalseForRegularArrays(){
		$this->assertFalse(Support::isStringKeyed(['a','b']));
		$this->assertFalse(Support::isStringKeyed(['a',['b' => 'x']]));
		$this->assertFalse(Support::isStringKeyed(['a']));
	}

	public function testStringKeyedIsTrueForEmpty(){
		$this->assertTrue(Support::isStringKeyed([]));
	}
	
	public function testStringKeyedIsTrueForAssocArrays(){
		$this->assertTrue(Support::isStringKeyed(['a' => 'b']));
		$this->assertTrue(Support::isStringKeyed(['a' => ['b', 'c']]));
	}

	public function testGetClassBasename(){
		$this->assertTrue(Support::getClassBasename($this) == 'SupportTest');
		$this->assertTrue(Support::getClassBasename(new Support) == 'Support');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetClassBasenameFail(){
		Support::getClassBasename(0);
	}

	public function testIsEmptyStringIsFalseForZero(){
		$this->assertFalse(Support::isEmptyString(0));
	}

	public function testIsEmptyStringIsFalseForBooleanFalse(){
		$this->assertFalse(Support::isEmptyString(false));
	}

	public function testIsEmptyStringIsFalseForSpacesOnlyString(){
		$this->assertFalse(Support::isEmptyString(' '));
	}

	public function testIsEmptyStringIsTrueWhenNullOrEmpty(){
		$this->assertTrue(Support::isEmptyString(null));
		$this->assertTrue(Support::isEmptyString(''));
	}

	public function testBoolToStringDoesntChangeNonBooleanValues(){
		$this->assertTrue(Support::boolToString(1) == 1);
		$this->assertTrue(Support::boolToString('1') == '1');
	}
	
	public function testBoolToStringConvertsToZeroAndOne(){
		$this->assertTrue(Support::boolToString(false) == '0');
		$this->assertTrue(Support::boolToString(true) == '1');
	}
	
	public function testBoolToStringConvertsToStringWithOptionalFlag(){
		$this->assertTrue(Support::boolToString(false, false) == 'false');
		$this->assertTrue(Support::boolToString(true, false) == 'true');
	}
	/**
	 * @dataProvider xmlProvider
	 */
	public function testCompareXMLStrings($s1, $s2, $b){
		$this->assertTrue(Support::compareXMLStrings($s1, $s2) == $b);
	}

	public function xmlProvider(){
		return [
			['<a>b</a>', '<a>b</a>', true],
			['<a>b</a>', ' <a> b </a> ', true],
			['<a>b</a>', '<b>a</b>', false]
		];
	}
}