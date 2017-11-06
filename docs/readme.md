# Documentation
* [Xmlify](xmlify.md)
* [XmlMessage](xmlmessage.md)
* [Support](support.md)

# Changelog

### v2.0.0 - 03.09.2017
* Added changelog
* Refactord Xmlify, might cause builds to fail if they were creating empty elements with attributes when building arrays of arrays.
* Removed XmlParser, XmlConverter and XsdValidator.
* Merged my PHP Utils library with this library under Support class.
* Version, encoding and data properties in XmlMessage are now private.
* XmlMessage now throws an exception if the build method doesn't exist.
* XmlMessage now checks if required property is in correct format.
* XmlMessage now checks if name and build properties are strings and only overwrites default values if they are.
* Updated documenation.

### v2.0.1 - 10.05.2017
* Made XmlMessage an abstract class as it should've been from the start.
* Removed build property from XmlMessage and made build an abstract function that must be implemented to adhere to the template pattern.
* Refactored support tests to smaller units.
* Updated docs to reflect slight change to XmlMessage.
* Removed unused functions from support class.

### v3.0.0 - 11.06.2017
* Removed custom exceptions in favour of PHP exceptions
* Deleted docs about exceptions
* Updated comment blocks for phpdoc conventions