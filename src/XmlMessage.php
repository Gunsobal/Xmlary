<?php

namespace Gunsobal\Xmlary;

use OutOfRangeException;
use BadMethodCallException;
use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\Support;

/**
 * This class provides a base upon which to build new outgoing XML messages. It is built following
 * Factory pattern conventions and uses underscores for its protected properties. In derivative 
 * classes, simply provide a build method returning an array suitable for Xmlify.
 *
 * @author Gunnar Örn Baldursson
 */
abstract class XmlMessage
{
    /** @var array $_data The array containing the data passed in through a constructor **/
    private $_data;

    /** @var string $_version Version head of XML document, defaults to 1.0 **/
    protected $_version;

    /** @var string $_encoding Encoding head of XML document, defaults to UTF-8 **/
    protected $_encoding;
    
    /** @var string $_name The name of the root element in the xml message, defaults to class name **/
    protected $_name;

    /** @var array $_required Each key is a required field in the message and its value is a custom error message **/
    protected $_required = [];

    /**
     * Constructor to initialize factory pattern and some default values
     * 
     * @param array $arr
     * @throws \BadMethodCallException
     */
    public function __construct($arr = []){
        $this->_data = $arr;
        $this->_version = $this->_version ?? '1.0';
        $this->_encoding = $this->_encoding ?? 'UTF-8';
        $this->_name = $this->_name ?? Support::getClassBasename($this);

        $this->validate($this->_required);
    }

    /**
     * Factory pattern get
     * 
     * @param string $field
     * @throws \OutOfRangeException
     * @return mixed
     */
    public function __get($field){
        if (array_key_exists($field, $this->_data)){
            return $this->_data[$field];
        } else {
            throw new OutOfRangeException("[$this->_name] Requested field '$field' was not found");
        }
    }

    /**
     * Convert this message to XML markup style string with no XML header
     * 
     * @see Gunsobal\Xmlify\stringify()
     * @throws \BadMethodCallException
     * @throws \UnexpectedValueException
     * @return string
     */ 
    public function toString(){
        return Xmlify::stringify([$this->_name => $this->build()]);
    }

    /**
     * Convert this message to XML string
     * 
     * @see Gunsobal\Xmlify\xmlify()
     * @throws \BadMethodCallException
	 * @throws \UnexpectedValueException
     * @return string Valid XML
     */ 
    public function toXml(){
        return Xmlify::xmlify([$this->_name => $this->build()], $this->_version, $this->_encoding)->saveXml();
    }

    /**
     * Convert this message to DOMDocument
     * 
     * @see Gunsobal\Xmlify\xmlify()
     * @throws \BadMethodCallException
	 * @throws \UnexpectedValueException
     * @return \DOMDocument
     */
     public function toDOM(){
         return Xmlify::xmlify([$this->_name => $this->build()], $this->_version, $this->_encoding);
     }

    /**
     * Verify fields exist in $_data property
     * 
     * @param array $fields - Assoc array where each key represents a required field and value represents an error message
     * @throws \BadMethodCallException
     * @return void
     */
    protected function validate($fields){
        foreach ($fields as $field => $message){
            try {
                $this->$field;
            } catch (OutOfRangeException $e){
                throw new BadMethodCallException("The key '$field' is required. $message");
            }
        }
    }

    /**
     * Define an array which will be passed to Xmlify
     * 
     * @return array
     */
    protected abstract function build();
}