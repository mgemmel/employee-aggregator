<?php
declare(strict_types=1);

namespace App\Models\users;

use SimpleXMLElement;

class AbstractXmlModel
{

    /**
     * @param SimpleXMLElement $simpleXMLElement
     */
    public function __construct(protected SimpleXMLElement $simpleXMLElement)
    {
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return string
     */
    public function __get(string $key)
    {
        if (isset($this->simpleXMLElement[$key])) {
            return (string)$this->simpleXMLElement[$key];
        }
        if (property_exists($this->simpleXMLElement, $key)) {
            return (string)$this->simpleXMLElement->{$key};
        }

        return '';
    }

    /**
     * @param AttributeModel[] $attributes
     * @return $this
     */
    public function update(array $attributes): static
    {
        foreach ($attributes as $attribute) {
            if (property_exists($this->simpleXMLElement, $attribute->getName())) {
                $xmlAttribute = $this->simpleXMLElement->{$attribute->getName()};
                $xmlAttribute[0] = $attribute->getValue();
            }else{
                //$child = $this->simpleXMLElement->addChild()
            }
        }

        return $this;
    }
}
