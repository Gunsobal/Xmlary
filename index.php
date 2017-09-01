<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlConverter;

interface itest1
{
    public function a();
}

interface itest2
{
    public function b();
}

class test implements itest1, itest2
{
    protected function a(){}

    public function b(){}
}

$a = new test();