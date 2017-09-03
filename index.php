<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

class TestMessage extends XmlMessage
{
    protected $_required = ['example' => 'message'];

    public function build(){
        return ['Nested' => $this->example];
    }
}

$x = new TestMessage(['example' => 'Val']);

echo $x->toString();