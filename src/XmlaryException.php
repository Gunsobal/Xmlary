<?php

namespace Gunsobal\Xmlary;

include_once 'Utils.php';

use \Exception;

/**
 * Parent class to any exceptions thrown in this library, to simplify catching if necessary
 */
class XmlaryException extends Exception { }

class XmlifyException extends XmlaryException { }
class XmlMessageException extends XmlaryException { }
class XmlParserException extends XmlaryException { }
 class XmlConverterException extends XmlaryException { }