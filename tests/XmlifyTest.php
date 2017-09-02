<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;
use Gunsobal\Xmlary\Support;
use Gunsobal\Xmlary\XmlaryException;

/**
 * Unit tests for Xmlify
 */
class XmlifyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Checking for syntax error in Xmlify
     */
    public function testIsThereAnySyntaxError(){
        $x = new Xmlify;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * Test argument fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testXmlifyArgumentFail(){
        Xmlify::xmlify(0);
    }

    
    /**
     * Test argument fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testHtmlifyArgumentFail(){
        Xmlify::htmlify(0);
    }

    /**
     * Test argument fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testStringifyArgumentFail(){
        Xmlify::stringify(0);
    }

    /**
     * Test invalid element fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testXmlifyInvalidElement(){
        Xmlify::xmlify(['???' => 'a']);
    }

    /**
     * Test invalid element fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testStringifyInvalidElement(){
        Xmlify::stringify(['xml' => 'a']);
    }

    /**
     * Test invalid element fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testHtmlifyInvalidElement(){
        Xmlify::htmlify(['!"#$' => 'a']);
    }

    /**
     * Test invalid attribute fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testXmlifyInvalidAttribute(){
        Xmlify::xmlify(['valid' => ['@attributes' => ['0' => 'a']]]);
    }

    /**
     * Test invalid attribute fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testStringifyInvalidAttribute(){
        Xmlify::stringify(['valid' => ['@attributes' => ['??' => 'a']]]);
    }

    /**
     * Test invalid attribute fail
     * @expectedException Gunsobal\Xmlary\XmlifyException
     */
    public function testHtmlifyInvalidAttribute(){
        Xmlify::htmlify(['valid' => ['@attributes' => ['#"$' => 'a']]]);
    }

    /**
     * Test stringify
     * @dataProvider simpleProvider
     */
    public function testStringify($a, $s){
        $x = Xmlify::stringify($a);
        $this->assertTrue(is_string($x));
        $this->assertTrue(Support::CompareXMLStrings($x, $s));
    }

    /**
     * Test htmlify
     */
     public function testHtmlify(){
         $s = Xmlify::htmlify(['a' => 'b']);
         $this->assertTrue(is_string($s));
     }

    public function simpleProvider(){
        $a = $this->getXmlifyArr();
        $s = $this->getXMLStringsArr('simple.xml');
        $data = [];
        for ($i = 0; $i < count($a); ++$i)
            $data[] = [$a[$i], $s[$i]];
        return $data;
    }

    public function domProvider(){
        $a = $this->getXmlifyArr();
        $s = $this->getXMLStringsArr('dom.xml');
        $data = [];
        for ($i = 0; $i < count($a); ++$i)
            $data[] = [$a[$i], $s[$i]];
        return $data;
    }

    private function getXMLStringsArr($filename){
        $file = file_get_contents(__DIR__ . '\\..\\resources\\' . $filename);
        return explode('%%', $file);
    }

    private function getXmlifyArr(){
        return [
            ['Solo' => 'a'],
            ['Solo' => ''],
            ['Solo' => [['@attributes' => ['a' => 'b']], 'A']],
            ['Solo' => ['@attributes' => ['a' => 'b']]],
            ['Simple' => ['@attributes' => ['class' => 1], 'Test' => [['@attributes' => ['p' => 'true']],'Singlest']]],
            ['Message' => ['Subject' => 'Some title','Content' => [
                    'Images' => ['Image' => ['a.png', 'b.png', 'c.png']],
                    'Text' => [ 'Header' => ['h1' => 'Bla'], 'Body' => ['P1', 'P2']]]
                ]
            ],
            ['Message' => ['@attributes' => ['class' => 'testmessage'],
                    'Subject' => [],
                    'Content' => ['@attributes' => ['class' => 'sigh'],
                        'Images' => 
                            ['Image' => [
                                ['@attributes' => ['figure' => 'img']],
                                'a.png', 'b.png',
                                ['@attributes' => []], '',
                                ['Pic' => ['A' => [['@attributes' => ['href' => 'a']], 'C'],'B' => 'D']]
                            ]
                        ],
                        'Text' => [[['@attributes' => ['test' => false, 'title' => 'b']],
                            ['Header' => ['@attributes' => ['hr' => 'hs'], 'h1' => 'Bla'],
                            'Body' => [['@attributes' => ['body' => 'page']], 'P1', ['@attributes' => []], 'P2' ]], ['@attributes' => ['a' => 'b']], []]
                        ]
                    ]
                ]
            ]
        ];
    }
}