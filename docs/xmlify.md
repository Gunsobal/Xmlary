# Xmlify
Xmlify is a class which recursively converts nested associative arrays into XML. 

The class needs the array to follow a few conventions in order to properly generate XML. Failure to follow these conventions in such a way it would produce invalid XML will throw an [XmlifyException](xmlaryexception.md).

The associative array to be converted into XML needs to be structured like an XML document, where there's initially a single root element with however many nested child elements. See __Example__ section for more details on how to build the associative array.

XML tags and attributes need to be valid according to [XML standards](https://www.w3schools.com/xml/xml_syntax.asp)

## Examples

__Example 1__
Creating the nested array follows a structure identical to XML where each key/value pair will get converted into an XML node named after the key containing the value.

```php
$arr = [
    'Message' => [
        'MessageHeader' => [
            'Sender' => 'John Doe',
            'Recipient' => [
                'Name' => 'Jane Doe',
                'Title' => 'Doctor'
            ],
            'NumOfRecipients' => 1
        ],
        'MessageBody' => 'I\'m a message',
        'MessageFooter' => [
            'MessageService' => 'Mail',
            'Sent' => true
        ]
    ]
];
```

Will get converted to

```xml
<Message>
  <MessageHeader>
    <Sender>John Doe</Sender>
    <Recipient>
      <Name>Jane Doe</Name>
      <Title>Doctor</Title>
    </Recipient>
    <NumOfRecipients>1</NumOfRecipients>
  </MessageHeader>
  <MessageBody>I'm a message</MessageBody>
  <MessageFooter>
    <MessageService>Mail</MessageService>
    <Sent>1</Sent>
  </MessageFooter>
</Message>
```

__NOTE:__ Boolean values will get converted to 1 or 0

__Example 2__
XML can have multiple nodes with the same name and since keys must be unique, XML of this type can be generated using regular arrays.

```php
$arr = [
    'Order' => [
        'Drinks' => [
            'Drink' => [
                'Water',
                'Coke',
                ''
            ]
        ],
        'Deserts' => [
            'Desert' => [
                [
                    'Type' => 'Cake',
                    'Flavour' => 'Delicious'
                ],
                [
                    'Type' => 'IceCream',
                    'Color' => 'Pink'
                ]
            ]
        ]
    ]
];
```

Will get converted to

```xml
<Order>
  <Drinks>
    <Drink>Water</Drink>
    <Drink>Coke</Drink>
    <Drink/>
  </Drinks>
  <Deserts>
    <Desert>
      <Type>Cake</Type>
      <Flavour>Delicious</Flavour>
    </Desert>
    <Desert>
      <Type>IceCream</Type>
      <Color>Pink</Color>
    </Desert>
  </Deserts>
</Order>
```

__NOTE:__ Keys pointing to an empty array or an empty string will generate an empty XML element

__Example 3__
Xmlify additionally features support for attributes by declaring an @attributes key which has an associative array as its value where each key/value pair is turned into key="value" attributes.

```php
$arr = [
    'Order' => [
        '@attributes' => [
            'id' => 1,
            'payment_method' => 'visa'
        ],
        'Products' => [
            'Product' => [
                [
                    '@attributes' => ['valid' => true, 'limited' => false],
                    'Number' => 1,
                    'Element' => [
                        '@attributes' => [
                            'name' => 'Tshirt'
                        ]
                    ]
                ],
                [
                    'Number' => 2,
                    'Element' => [
                        '@attributes' => [
                            'name' => 'Pants'
                        ]
                    ]
                ],
            ]
        ]
    ]
];
```

Will get converted to

```xml
<Order id="1" payment_method="visa">
  <Products>
    <Product valid="1" limited="0">
      <Number>1</Number>
      <Element name="Tshirt"/>
    </Product>
    <Product>
      <Number>2</Number>
      <Element name="Pants"/>
    </Product>
  </Products>
</Order>
```

__Example 4__
Attributes can be attached to elements that are not associative arrays, and therefore can't have an @attributes key, by wrapping that element in an array where one value is an array with an @attributes key.

```php
$arr = [
    'Order' => [
        'Product' => [
            ['@attributes' => ['id' => 1]],
            'T-Shirt'
        ]
    ]
];
```

Will get converted to 

```xml
<Order>
  <Product id="1">T-Shirt</Product>
</Order>
```

__Example 5__
Attributes may also be attached to repeated elements that are not associative arrays by specifying an element that's an array holding a single key for attributes. Those attributes get applied to all subsequent elements unless they are overwritten.

```php
$arr = [
    'Drinks' => [
        'Drink' => [
            [
                '@attributes' => ['Color' => 'Pink']
            ],
            'Cherry',
            'Apple',
            [
                '@attributes' => ['Color' => 'Green']
            ],
            'Lime',
            [
                '@attributes' => []
            ],
            'Coke',
            [
                '@attributes' => []
            ],
        ]
    ]
];
```

Will get converted to 

```xml
<Drinks>
  <Drink Color="Pink">Cherry</Drink>
  <Drink Color="Pink">Apple</Drink>
  <Drink Color="Green">Lime</Drink>
  <Drink>Coke</Drink>
</Drinks>
```

__NOTE:__ Xmlify will not render an XML node when processing array of arrays. To get an empty element with attributes, add an empty element after the attributes array.

## API
Xmlify provides access to 3 static functions

### Function xmlify
The xmlify function will recursively build a DOMDocument object and return it so the script calling this function can further manipulate the object such as validate against an .xsd schema. You can use DOMDocument's saveXML() function to get your XML string. The xmlify function requires the array as a parameter and optional parameters for the document version and encoding.

### Function stringify
The stringify function uses string concatenation to generate a string with XML markup. It will not contain the <?xml version="1.0"?> head, like the xmlify function, as its original purpose was to generate XML which would get nested within other XML documents. It accepts the associative array as a required parameter along with an optional parameter to specify the starting tab indentation, default 0.

### Function htmlify
This is just a wrapper around calling htmlspecialchars on the stringify function output. It will return the same string as the stringify except with html entitites where applicable. It accepts the same paramters as stringify.