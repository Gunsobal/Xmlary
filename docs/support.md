# Support
Support is a collection of utility public static functions.

## API
```php
/**
 * Check if an array is string keyed
 * 
 * @param array $arr Array to check for string keys
 * @return boolean
 */
static public function isStringKeyed($arr)

/**
 * Get a class' basename
 * 
 * @param object $class Class object
 * @throws \InvalidArgumentException
 * @return string
 */
public static function getClassBasename($class)

/**
 * Check for empty string
 * 
 * @param string $str
 * @return boolean
 */
static public function isEmptyString($str)

/**
 * Convert bool to string
 * 
 * @param mixed $b - variable to convert from bool to string
 * @param boolean $num - True to get numeric values from bool, false for strings
 * @return mixed Returns $b unless it's a bool, then it returns a string
 */
static public function boolToString($b, $num = true)

/**
 * Compare XML strings
 * 
 * @param string $s1 
 * @param string $s2
 * @return boolean
 */
public static function compareXMLStrings($s1, $s2)
```