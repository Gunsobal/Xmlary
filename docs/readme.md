# Documentation
* [Xmlify](xmlify.md)
* [XmlMessage](xmlmessage.md)
* [XmlaryException](xmlexception.md)
* [Support](support.md)

# Changelog

## v2.0.0 - 02.09.2017
* Added changelog
* Refactord Xmlify, might cause builds to fail if they were creating empty elements with attributes when building arrays of arrays.
* Removed XmlParser, XmlConverter and XsdValidator.
* Updated documenation.
* Merged my PHP Utils library with this library under Support class.
* Version, encoding and data properties in XmlMessage are now private.
* XmlMessage now throws an exception if the build method doesn't exist.