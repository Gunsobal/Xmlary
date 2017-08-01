<?php

namespace Gunsobal\Xmlary;

include_once 'XmlaryException.php';
include_once 'Utils.php';

use Xmlify;

/**
 * XML Message base
 *
 * This class provides a base upon which to build new outgoing XML messages. It is built following
 * Factory pattern conventions and uses underscores for its protected properties. In derivative 
 * classes, simply provide a build method returning an array suitable for Xmlify.
 *
 * @author Gunnar Baldursson
 */
class XmlMessage
{
    /** @var string $_name The name of the root element in the xml message, defaults to class name **/
    protected $_name;

    /** @var string $_build The name of the derivative class' build method which returns an array for xmlify, defaults to build **/
    protected $_build;

    /** @var array $_data The array containing the data passed in through a constructor **/
    protected $_data;

    /** @var string $_version Version head of XML document, defaults to 1.9 **/
    protected $_version;

    /** @var string $_encoding Encoding head of XML document, defaults to UTF-8 **/
    protected $_encoding;

    /** @var array $_required Overwrite in derivative class in a way that each key in $_required is a requied field in the message, with its value being a custom error message **/
    protected $_required = [];

    public function __construct($arr){
        $this->_data = $arr;
        $this->_name = Utils::class_basename($this); // Set this class' name
        $this->_build = 'build'; // Set this class' build function. default build
        $this->_version = '1.0'; // Defaults for version and encoding
        $this->_encoding = 'UTF-8';
        $this->validate($this->_required);
    }

    public function __get($field){
        if (array_key_exists($field, $this->_data)){
            return $this->_data[$field];
        } else {
            throw new XmlMessageException("[$this->_name] Requested field '$field' was not found");
        }
    }

    /**
     * Convert this message to XML markup style string
     *
     * Calls whatever method is specified in the $_build property and builds an xml string with the $_name property as the root. 
     * Uses Xmlify by default
     *
     * @return string
     */ 
    public function toString(){
        return Xmlify::stringify([$this->_name => $this->{$this->_build}()]); //$this->_{$this->_build}() syntax to call this class' build function
    }

    /**
     * Convert this message to XML string
     *
     * Calls whatever method is specified in the $_build property and builds an xml string with the $_name property as the root. 
     * Uses Xmlify by default
     *
     * @return string Valid XML
     */ 
    public function toXml(){
        return Xmlify::xmlify_array([$this->_name => $this->{$this->_build}()], $this->_version, $this->_encoding)->saveXml();
    }

    /**
     * Convert this message to DOMDocument
     *
     * Calls whatever method is specified in the $_build property and builds DOMDocument using Xmlify.
     *
     * @return \DOMDocument
     */
     public function toDOM(){
         return Xmlify::xmlify_array([$this->_name => $this->{$this->_build}()], $this->_version, $this->_encoding);
     }

    /**
     * Validate fields in $_data property
     *
     * Verifies all fields specified in paramter exist in $_data property or else raises an exception with a custom defined message
     *
     * @param array $fields - Assoc array where each key represents a required field and value represents an error message
     */
    protected function validate($fields){
        foreach ($fields as $field => $message){
            try {
                $this->$field;
            } catch (XmlMessageException $e){
                throw new XmlMessageException("[$this->_name] The key '$field' is required. $message");
            }
        }
    }
}