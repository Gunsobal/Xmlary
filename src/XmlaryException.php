<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';

use \Exception;

/**
 * Parent class to any exceptions thrown in this library, to simplify catching if necessary
 */
class XmlaryException extends Exception { }

/**
 * Exception specific to Xmlify class
 */
class XmlifyException extends XmlaryException { }