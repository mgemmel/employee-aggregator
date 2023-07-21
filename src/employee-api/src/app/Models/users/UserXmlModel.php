<?php

namespace App\Models\users;


use SimpleXMLElement;

class UserXmlModel
{
    readonly int $id;
    public string $name;
    public int $age;

    /**
     * @param SimpleXMLElement $simpleXMLElement
     */
    public function __construct(private SimpleXMLElement $simpleXMLElement)
    {
        $this->id = (int)(string)$this->simpleXMLElement['id'];
        $this->name = $this->simpleXMLElement->name;
        $this->age = (int)(string)$this->simpleXMLElement->age;
    }

}
