<?php

namespace Gunsobal\Xmlary;

use Gunsobal\Xmlary\Support;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Gunsobal\Xmlary\XmlMessage
 * 
 * @author Gunnar Örn Baldursson
 */
class XmlMessageTest extends TestCase
{
    public function testIsThereAnySyntaxError(){
        $x = new AMessage([]);
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testMissingRequiredFields(){
        $x = new TestMessage([]);
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testCallingPropertyThatDoesntExist(){
        $x = new TestMessage(['example' => 'foo']);
        $x->foo();
    }

    public function testToStringAutomated(){
        $x = new TestMessage(['example' => 'Val']);
        $s = $x->toString();
        $this->assertTrue(is_string($s));
        $this->assertTrue(Support::compareXMLStrings($s, '<?xml version="1.0"?><TestMessage><Nested>Val</Nested></TestMessage>'));
    }

    public function testToXmlAutomated(){
        $x = new TestMessage(['example' => 'Val']);
        $s = $x->toXml();
        $this->assertTrue(is_string($s));
        $this->assertTrue(Support::compareXMLStrings($s, '<?xml version="1.0" encoding="UTF-8" ?><TestMessage><Nested>Val</Nested></TestMessage>'));
    }

    public function testToDOMAutomated(){
        $x = new TestMessage(['example' => 'Val']);
        $s = $x->toDOM();
        $this->assertTrue(is_a($s, 'DOMDocument'));
        $this->assertTrue(Support::compareXMLStrings($s->saveXML(), '<?xml version="1.0" encoding="UTF-8" ?><TestMessage><Nested>Val</Nested></TestMessage>'));
    }

    public function testToStringWithOverwrites(){
        $x = new AMessage([]);
        $s = $x->toString();
        $this->assertTrue(is_string($s));
        $this->assertTrue(Support::compareXMLStrings($s, '<?xml version="1.0" ?><NewMessage><Nested>Val</Nested></NewMessage>'));
    }

    public function testToXmlWithOverwrites(){
        $x = new AMessage([]);
        $s = $x->toXml();
        $this->assertTrue(is_string($s));
        $this->assertTrue(Support::compareXMLStrings($s, '<?xml version="1.0" encoding="UTF-8" ?><NewMessage><Nested>Val</Nested></NewMessage>'));
    }

    public function testToDOMWithOverwrites(){
        $x = new AMessage([]);
        $s = $x->toDOM();
        $this->assertTrue(is_a($s, 'DOMDocument'));
        $this->assertTrue(Support::compareXMLStrings($s->saveXML(), '<?xml version="1.0" encoding="UTF-8" ?><NewMessage><Nested>Val</Nested></NewMessage>'));
    }
}

class TestMessage extends XmlMessage
{
    protected $_required = ['example' => 'message'];

    public function build(){
        return ['Nested' => $this->example];
    }

    public function foo(){
        $this->foo;
    }
}

class AMessage extends XmlMessage
{
    protected $_name = 'NewMessage';

    public function build(){
        return ['Nested' => 'Val'];
    }
}