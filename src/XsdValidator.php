<?php

namespace Gunsobal\Xmlary;

include_once 'XmlParser.php';

use Gunsobal\Xmlary\XmlParser;

/**
 * XSD Validator
 *
 * Validates xml against an xsd schema
 *
 * @author Gunnar Baldursson
 */
class XsdValidator
{
    /**
     * Validates XML against schema
     *
     * Uses Parser to create DOMDocument from XML and runs the schemaValidate method
     *
     * @param mixed $xml valid XML as string, SimpleXMLElement or DOMDocument
     * @param string $xsd Schema to validate
     * @return boolean
     */
    public static function validate($xml, $xsd){
        return XmlParser::toDom($xml)->schemaValidate($xsd);
    }
}