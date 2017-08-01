<?php

namespace Gunsobal\Xmlary;

include_once 'XmlParser.php';

use Gunsobal\Xmlary\XmlParser;

/**
 * XSD Validator
 *
 * Validates XML against an XSD schema
 *
 * @author Gunnar Baldursson
 */
class XsdValidator
{
    /**
     * Validates XML against XSD schema
     * @param mixed $xml valid XML as string, SimpleXMLElement or DOMDocument
     * @param string $xsd Schema to validate
     * @return boolean
     */
    public static function validate($xml, $xsd){
        return XmlParser::toDom($xml)->schemaValidate($xsd);
    }
}