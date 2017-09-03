# Support
Support is a collection of utility functions, some of which are used by this package.

## API
```php
/**
* Check if an array is string keyed
* @param array $arr Array to check for string keys
* @return boolean
*/
function isStringKeyed($arr)

/**
* Function to get a class' basename
* @param object $class Class object
* @return string 
*/
function getClassBasename($class)

/**
* Check for empty string
* @param string $str
* @return boolean
*/
function isEmptyString($str)

/**
* Check for json string
* @param string $str
* @return boolean
*/
function isJson($str)

/**
* Convert bool to string
* @param mixed $b - variable to convert from bool to string
* @param boolean $num - True to get numeric values from bool, false for strings
* @return mixed - Returns $b unless it's a bool, then it returns a string
*/
function boolToString($b, $num = true)

/**
* Xubstring to first occurrence of a substring in a string if any such substring exists
* @param string $str - String to cut
* @param string $s1 - Target substring
* @return string
*/
function substrToFirst($str, $s1)

/**
* Xubstring to last occurrence of a substring in a string if any such substring exists
* @param string $str - String to cut
* @param string $s1 - Target substring
* @return string
*/
function substrToLast($str, $s1)

/**
* Compare XML strings
* @param string $s1
* @param string $s2
* @return boolean
*/
function compareXMLStrings($s1, $s2){
```