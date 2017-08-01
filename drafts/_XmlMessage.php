<?php

namespace App\XmlMessages;

use Xmlify;
use \Exception;

/**
 * Class to be inherited by any Message class to send via Hekla Gateway setup with factory pattern. 
 * Create child class with name of message to be sent (root element of xml to be generated), implement a build method or overwrite $name and $build
 * Set protected $_required = ['field' => 'message'] with any required fields with that errormessage
 * Implement build function in a way that works for Xmlify class or overwrite the toString / toXml methods
 */
class _XmlMessage
{
    protected $_name;
    protected $_build;
    protected $_data;
    protected $_version;
    protected $_encoding;
    protected $_required = [];

    public function __construct($arr){
        $this->_data = $arr;
        $this->_name = \class_basename($this); // Set this class' name
        $this->_build = 'build'; // Set this class' build function. default build
        $this->_version = '1.0'; // Defaults for version and encoding
        $this->_encoding = 'UTF-8';
        $this->validate($this->_required);
    }

    /**
     * Magic getter throws exception if requested field is not found
     */
    public function __get($field){
        if (array_key_exists($field, $this->_data)){
            return $this->_data[$field];
        } else {
            throw new Exception("[$this->_name] Requested field '$field' was not found");
        }
    }

    /**
     * Return this message as string with xml markup but without xml head, default uses Xmlify
     * @return \String
     */ 
    public function toString(){
        return Xmlify::stringify([$this->_name => $this->{$this->_build}()]); //$this->_{$this->_build}() syntax to call this class' build function
    }

    /**
     * Return this message as xml string, default uses Xmlify
     * @return \String - Xml
     */
    public function toXml(){
        return Xmlify::xmlify_array([$this->_name => $this->{$this->_build}()], $this->_version, $this->_encoding)->saveXml(); //$this->_{$this->_build}() syntax to call this class' build function
    }

    /**
     * Verifies all fields in array exist in data, else raises exception with message
     * @param \Array $fields - Assoc array where each key represents a required field and value represents an error message
     */
    protected function validate($fields){
        foreach ($fields as $field => $message){
            try {
                $this->$field;
            } catch (Exception $e){
                throw new Exception("[$this->_name] The key '$field' is required. $message");
            }
        }
    }
}