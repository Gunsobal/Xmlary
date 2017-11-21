<?php

namespace Gunsobal\Xmlary;

use PHPUnit_Framework_TestCase;
use Gunsobal\Xmlary\Support;

/**
 * Unit tests for XmlMessage
 */
class XmlMessageTest extends PHPUnit_Framework_TestCase
{
    public function testIsThereAnySyntaxError(){
        $x = new AMessage([]);
        $this->assertTrue(is_object($x));
        unset($x);
    }

    /**
     * @expectedException Gunsobal\Xmlary\XmlaryException
     */
    public function testMissingRequiredFields(){
        $x = new TestMessage([]);
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
}

class AMessage extends XmlMessage
{
    protected $_name = 'NewMessage';

    public function build(){
        return ['Nested' => 'Val'];
    }
}