<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

class FailMessage extends XmlMessage
{
    protected $_build = 'buildfunc';
}

$x = new FailMessage([]);