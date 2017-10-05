# XmlMessage
This class acts as a superclass for classes which build XML documents by utilizing the factory pattern. Its purpose is to provide a convenient base for classes which encapsulate logic to generate XML using Xmlify by following a few simple conventions. 

If you are unfamiliar with Xmlify then review the documentation for it [here](xmlify.md).

XmlMessage throws XmlMessageException if there's an error.

## Example
In this example, we want to create a php class which has the single responsibility of generating the XML for a product order.

The order might look something like this once converted to XML:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<ProductOrder>
    <Buyer>
        <Name>John Doe</Name>
        <Title>Developer</Title>
    </Buyer>
    <Products>
        <Product id="1">
            <ProductID>13344</ProductID>
            <Manufacturer>Amazon</Manufacturer>
        </Product>
        <Product id="2">
            <ProductID>11119</ProductID>
            <Manufacturer>Amazon</Manufacturer>
        </Product>
    </Products>
</ProductOrder>
```

The first convenient feature of XmlMessage is that the name we give our class will become the root element of our XML. Next we simply have to define a function called "build" which returns the content of our root element.

```php
use Gunsobal\Xmlary\XmlMessage;

class ProductOrder extends XmlMessage
{
    public function build(){
        return [
            'Buyer' => [
                'Name' => 'John Doe',
                'Title' => 'Developer'
            ],
            'Products' => [
                'Product' => [
                    [
                        '@attributes' => ['id' => 1],
                        'ProductID' => '13344',
                        'Manufacturer' => 'Amazon'
                    ],
                    [
                        '@attributes' => ['id' => 2],
                        'ProductID' => '11119',
                        'Manufacturer' => 'Amazon'
                    ]
                ]
            ]
        ];
    }
}
```

Now if we run the following code we get the exact same output as the XML message described above

```php
$msg = new ProductOrder();
$msg->toXml();
```

* __NOTE:__ The build function does not need to be public, but it's often convenient in case this particular bit of XML generation needs to be re-used.

However we do not want to hardcode the values for our XML so we need to specify some parameters for our class. Ideally we would want to have properties for any of the fields which contain values that might change so we would do the following changes to our class

```php
class ProductOrder extends XmlMessage
{
    public function build(){
        $build = [
            'Buyer' => [
                'Name' => $this->buyer->name,
                'Title' => $this->buyer->title,
            ]
        ];
        for ($i = 0; $i < count($this->products); ++$i){
            $build['Products']['Product'][] = [
                '@attributes' => ['id' => $i + 1],
                'ProductID' => $this->products[$i]->id,
                'Manufacturer' => $this->products[$i]->manufacturer
            ];
        }
        return $build;
    }
}
```

Now we can simply pass a buyer object and an array of product objects to our message and it will generate the same XML as before.

```php
$b = (object) [ 'name' => 'John Doe', 'title' => 'Developer' ];
$p1 = (object) [ 'id' => '13344', 'manufacturer' => 'Amazon' ];
$p2 = (object) [ 'id' => '11119','manufacturer' => 'Amazon' ];
$msg = new ProductOrder([
    'buyer' => $b,
    'products' => [$p1, $p2]
]); 
$msg->toXml();
```

* __NOTE:__ Any key defined in the array passed into the XML message will be accessible as a property in that class as per the factory pattern.

To facilitate defensive programming, it is easy to setup the class so it throws an XmlMessageException if it doesn't get the properties it needs to generate the XML. All that needs to be done is specifying a property with an array of required properties where each key corresponds to the property, and its value will be the message displayed with the exception.

```php
protected $_required = [
    'buyer' => 'Needs to be a buyer object with name and title properties',
    'products' => 'Needs to be an array of product objects with id and manufacturer properties'
];
```

* __NOTE:__ You can also define a `protected $_name` property to change the name of the root element of the XmlMessage.

## API
Classes inheriting the XmlMessage class will get set up with a factory pattern as well as some properties to control the configuration of the XML generation. Any settings properties defined in the base class will have an underscore prefix in their name.

### Functions

```php
/**
* Convert this message to XML markup style string with no XML header
* @return string
*/ 
public function toString()

/**
* Convert this message to XML string
* @return string Valid XML
*/ 
public function toXml()

/**
* Convert this message to DOMDocument
* @return \DOMDocument
*/
public function toDOM()
```

### Configurable properties
```php
/** @var string $_name The name of the root element in the xml message, defaults to class name **/
protected $_name;

/** @var array $_required Each key is a required field in the message and its value is a custom error message **/
protected $_required = [];
```

### Reserved properties
```php
/** @var array $_data The array containing the data passed in through the constructor **/
private $_data;

/** @var string $_version Version head of XML document, defaults to 1.0 **/
private $_version;

/** @var string $_encoding Encoding head of XML document, defaults to UTF-8 **/
private $_encoding;
```