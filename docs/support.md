# Support
Support is a collection of utilities

## API
```php
/**
* Utility function to check if an array is string keyed
* @param array $arr Array to check for string keys
* @return boolean
*/
function isStringKeyed($arr)

/**
* Utility function to get a class' basename
* @param object $class Class object
* @return string 
*/
function getClassBasename($class)

/**
* Utility function to check for empty string
* @param string $str
* @return boolean
*/
function isEmptyString($str)

/**
* Utility function to check for json string
* @param string $str
* @return boolean
*/
function isJson($str)

/**
* Utility function to convert bool to string
* @param mixed $b - variable to convert from bool to string
* @param boolean $num - True to get numeric values from bool, false for strings
* @return mixed - Returns $b unless it's a bool, then it returns a string
*/
function boolToString($b, $num = true)

/**
* Utility function to substring to first occurrence of a substring in a string if any such substring exists
* @param string $str - String to cut
* @param string $s1 - Target substring
* @return string
*/
function substrToFirst($str, $s1)

/**
* Utility function to substring to last occurrence of a substring in a string if any such substring exists
* @param string $str - String to cut
* @param string $s1 - Target substring
* @return string
*/
function substrToLast($str, $s1)

/**
* Utility function to compare XML strings
* @param string $s1
* @param string $s2
* @return boolean
*/
function compareXMLStrings($s1, $s2){
```