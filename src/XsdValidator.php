<?php

namespace Gunsobal\Xmlary;

use Gunsobal\Xmlary\XmlConverter;

/**
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
        return XmlConverter::toDom($xml)->schemaValidate($xsd);
    }
}