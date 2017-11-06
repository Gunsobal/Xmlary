# Xmlary

## Introduction
A collection of helper classes I've made to simplify my workflow when using lots of XML with php and extracted into a composer package. The primary function of the package is to generate XML from associative arrays and provide a convenient platform for encapsulating that XML generation in its own object. 

Install the package into your project by running "composer require gunsobal/xmlary", or download the source code here on GitHub, and any class included in this package will be in the Gunsobal\Xmlary namespace.

## Documentation
You can review the documentation [here on GitHub](https://github.com/Gunsobal/Xmlary/tree/master/docs#readme).
* [Xmlify](docs/xmlify.md)
* [XmlMessage](docs/xmlmessage.md)
* [Support](docs/support.md)

## License
Xmlary is open-sourced software licensed under the MIT License

## Resources
Composer package is based on the [template](http://www.darwinbiler.com/creating-composer-package-library/) by Darwin Biler.

## Changelog

### 03.09.2017
* Added changelog
* Refactord Xmlify, might cause builds to fail if they were creating empty elements with attributes when building arrays of arrays.
* Removed XmlParser, XmlConverter and XsdValidator.
* Merged my PHP Utils library with this library under Support class.
* Version, encoding and data properties in XmlMessage are now private.
* XmlMessage now throws an exception if the build method doesn't exist.
* XmlMessage now checks if required property is in correct format.
* XmlMessage now checks if name and build properties are strings and only overwrites default values if they are.
* Updated documenation.
* Added v2.0.0 tag.

### 10.05.2017
* Made XmlMessage an abstract class as it should've been from the start.
* Removed build property from XmlMessage and made build an abstract function that must be implemented to adhere to the template pattern.
* Refactored support tests to smaller units.
* Updated docs to reflect slight change to XmlMessage.
* Removed unused functions from support class.

### 11.06.2017
* Removed custom exceptions in favour of PHP exceptions.
* Deleted docs about exceptions.
* Updated comment blocks for phpdoc conventions.

## Issues
If you encounter any bugs or issues please report them here on GitHub so I can look into them.