<?php

namespace Gunsobal\Xmlary;

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\Support;
use Gunsobal\Xmlary\XmlaryException;

/**
 * This class provides a base upon which to build new outgoing XML messages. It is built following
 * Factory pattern conventions and uses underscores for its protected properties. In derivative 
 * classes, simply provide a build method returning an array suitable for Xmlify.
 *
 * @author Gunnar Baldursson
 */
abstract class XmlMessage
{
    /** @var array $_data The array containing the data passed in through a constructor **/
    private $_data;

    /** @var string $_version Version head of XML document, defaults to 1.0 **/
    private $_version;

    /** @var string $_encoding Encoding head of XML document, defaults to UTF-8 **/
    private $_encoding;
    
    /** @var string $_name The name of the root element in the xml message, defaults to class name **/
    protected $_name;

    /** @var array $_required Each key is a required field in the message and its value is a custom error message **/
    protected $_required = [];

    public function __construct($arr = []){
        $this->_data = $arr;
        $this->_version = '1.0';
        $this->_encoding = 'UTF-8';
        $this->_name = $this->_name ?? Support::getClassBasename($this);

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
     * Convert this message to XML markup style string with no XML header
     * @return string
     */ 
    public function toString(){
        return Xmlify::stringify([$this->_name => $this->build()]);
    }

    /**
     * Convert this message to XML string
     * @return string Valid XML
     */ 
    public function toXml(){
        return Xmlify::xmlify([$this->_name => $this->build()], $this->_version, $this->_encoding)->saveXml();
    }

    /**
     * Convert this message to DOMDocument
     * @return \DOMDocument
     */
     public function toDOM(){
         return Xmlify::xmlify([$this->_name => $this->build()], $this->_version, $this->_encoding);
     }

    /**
     * Verify fields exist in $_data property
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

    /**
     * Define an array which will be passed to Xmlify
     * @return array
     */
    protected abstract function build();
}