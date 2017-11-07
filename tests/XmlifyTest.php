<?php

namespace Gunsobal\Xmlary;

use Gunsobal\Xmlary\Support;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Gunsobal\Xmlary\Xmlify
 * 
 * @author Gunnar Örn Baldursson
 */
class XmlifyTest extends TestCase
{
    public function testIsThereAnySyntaxError(){
        $x = new Xmlify;
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testXmlifyArgumentFail(){
        Xmlify::xmlify(0);
    }

    
    /**
     * @expectedException BadMethodCallException
     */
    public function testHtmlifyArgumentFail(){
        Xmlify::htmlify(0);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testStringifyArgumentFail(){
        Xmlify::stringify(0);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testXmlifyInvalidElement(){
        Xmlify::xmlify(['???' => 'a']);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testStringifyInvalidElement(){
        Xmlify::stringify(['xml' => 'a']);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testHtmlifyInvalidElement(){
        Xmlify::htmlify(['!"#$' => 'a']);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testXmlifyInvalidAttribute(){
        Xmlify::xmlify(['valid' => ['@attributes' => ['0' => 'a']]]);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testStringifyInvalidAttribute(){
        Xmlify::stringify(['valid' => ['@attributes' => ['??' => 'a']]]);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testHtmlifyInvalidAttribute(){
        Xmlify::htmlify(['valid' => ['@attributes' => ['#"$' => 'a']]]);
    }

    /**
     * @dataProvider simpleProvider
     */
    public function testStringify($a, $s){
        $x = Xmlify::stringify($a);
        $this->assertTrue(is_string($x));
        $this->assertTrue(Support::compareXMLStrings($x, $s));
    }

    /**
     * @dataProvider domProvider
     */
    public function testXmlify($a, $s){
        $x = Xmlify::xmlify($a);
        $this->assertTrue(is_a($x, 'DOMDocument'));
        $this->assertTrue(Support::compareXMLStrings($x->saveXML(), $s));
    }

     public function testHtmlify(){
         $s = Xmlify::htmlify(['a' => 'b']);
         $this->assertTrue(is_string($s));
        }
        

    /**
     * Provides arrays as param1 and expected SimpleXML output as param2
     */
    public function simpleProvider(){
        $a = $this->makeXmlifyExampleArrays();
        $s = $this->loadXMLStrings('simple.xml');
        $data = [];
        for ($i = 0; $i < count($a); ++$i)
            $data[] = [$a[$i], $s[$i]];
        return $data;
    }

    /**
     * Provides arrays as param1 and expected DOM output as param2
     */
    public function domProvider(){
        $a = $this->makeXmlifyExampleArrays();
        $s = $this->loadXMLStrings('dom.xml');
        $data = [];
        for ($i = 0; $i < count($a); ++$i)
            $data[] = [$a[$i], $s[$i]];
        return $data;
    }

    /**
     * Gets XML strings from resources folder
     * 
     * @param string $filename
     * @return array
     */
    private function loadXMLStrings($filename){
        $file = file_get_contents(join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', $filename]));
        return explode('%%', $file);
    }

    /**
     * Returns array of Xmlify test arrays
     * 
     * @return array
     */
    private function makeXmlifyExampleArrays(){
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