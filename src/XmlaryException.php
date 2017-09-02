<?php

namespace Gunsobal\Xmlary;

use \Exception;

/**
 * Parent class to any exceptions thrown in this library, to simplify catching if necessary
 */
class XmlaryException extends Exception { }
class XmlifyException extends XmlaryException { }
class XmlMessageException extends XmlaryException { }