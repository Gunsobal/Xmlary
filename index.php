<?php

require 'vendor/autoload.php';

use Gunsobal\Xmlary\Xmlify;
use Gunsobal\Xmlary\XmlMessage;

class ProductOrder extends XmlMessage
{
    public function build(){
        $build = [
            'Buyer' => [
                'Name' => $this->buyer->name,
                'Title' => $this->buyer->title,
            ],
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

$b = (object) [ 'name' => 'John Doe', 'title' => 'Developer' ];
$p1 = (object) [ 'id' => '13344', 'manufacturer' => 'Amazon' ];
$p2 = (object) [ 'id' => '11119','manufacturer' => 'Amazon'];
$msg = new ProductOrder([
    'buyer' => $b,
    'products' => [$p1, $p2]
]); 
echo $msg->toXml();