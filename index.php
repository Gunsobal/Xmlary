<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

class FailMessage extends XmlMessage
{
    protected $_required = ['abc' => ['mommy']];
    protected $_build = 'buildfunc';

    public function buildfunc(){

    }
}

$x = new FailMessage([]);