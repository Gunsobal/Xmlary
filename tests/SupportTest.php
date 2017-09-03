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
		$this->assertTrue(Support::isStringKeyed(['a' => ['b' => 'c']]));
		$this->assertFalse(Support::isStringKeyed(['a','b']));
		$this->assertFalse(Support::isStringKeyed(['a',['b' => 'x']]));
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

	public function testIsEmptyString(){
		$this->assertFalse(Support::isEmptyString(0));
		$this->assertFalse(Support::isEmptyString(false));
		$this->assertFalse(Support::isEmptyString(' '));
		$this->assertTrue(Support::isEmptyString(null));
		$this->assertTrue(Support::isEmptyString(''));
	}

	public function testIsJson(){
		$this->assertFalse(Support::isJson('1234'));
		$this->assertFalse(Support::isJson(1234));
		$this->assertFalse(Support::isJson(''));
		$this->assertTrue(Support::isJson('{}'));
		$this->assertTrue(Support::isJson('{"a":"b"}'));
	}

	public function testBoolToString(){
		$this->assertTrue(Support::boolToString(false) == '0');
		$this->assertTrue(Support::boolToString(true) == '1');
		$this->assertTrue(Support::boolToString(false, false) == 'false');
		$this->assertTrue(Support::boolToString(true, false) == 'true');
		$this->assertTrue(Support::boolToString(1) == 1);
		$this->assertTrue(Support::boolToString('1') == '1');
	}

	public function testSubstrToFirst(){
		$this->assertTrue(Support::substrToFirst('a b', ' ') == 'a');
		$this->assertTrue(Support::substrToFirst('a ba', ' ba') == 'a');
		$this->assertTrue(Support::substrToFirst('a b a', ' ') == 'a');
		$this->assertTrue(Support::substrToFirst('a', ' ') == 'a');
		$this->assertTrue(Support::substrToFirst('a', 'a') == '');
	}

	public function testSubstrToLast(){
		$this->assertTrue(Support::substrToLast('a b', ' ') == 'a');
		$this->assertTrue(Support::substrToLast('a ba', ' ba') == 'a');
		$this->assertTrue(Support::substrToLast('a b a', ' ') == 'a b');
		$this->assertTrue(Support::substrToLast('a', ' ') == 'a');
		$this->assertTrue(Support::substrToLast('a', 'a') == '');
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