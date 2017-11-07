<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

class Test extends XmlMessage
{
    private $_data = [];

    protected function build(){

    }

    public function foo(){
        var_dump($this->_data);
        var_dump($this->foo);
    }
}

$t = new Test(['foo' => 'bar']);

$d = new DOMDocument("1.1", "ISO-8859-1");
$n = $d->createElement('foo', 'bar');
$d->appendChild($n);
echo $d->saveXML();