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
     * @param string $propertyName
     * @return bool
     */
    private function hasProperty(string $propertyName): bool
    {
        return property_exists($this->simpleXMLElement, $propertyName);
    }

    /**
     * @param string $propertyName
     * @return string
     */
    private function getPropertyValue(string $propertyName): string
    {
        return (string)$this->simpleXMLElement->{$propertyName};
    }

    /**
     * @param string $propertyName
     * @return SimpleXMLElement
     */
    private function getProperty(string $propertyName): SimpleXMLElement
    {
        return $this->simpleXMLElement->{$propertyName};
    }

    /**
     * @param string $attributeName
     * @return bool
     */
    private function hasAttribute(string $attributeName): bool
    {
        return isset($this->simpleXMLElement[$attributeName]);
    }

    /**
     * @param string $attributeName
     * @return string
     */
    private function getAttributeValue(string $attributeName): string
    {
        return (string)$this->simpleXMLElement[$attributeName];
    }

    /**
     * @param string $name
     * @param string $value
     * @return SimpleXMLElement
     */
    private function addProperty(string $name, string $value): SimpleXMLElement
    {
        return $this->simpleXMLElement->addChild($name, $value);
    }


    /**
     * Dynamically retrieve attributes and properties on the model.
     *
     * @param string $key
     * @return string
     */
    public function __get(string $key)
    {
        if ($this->hasAttribute($key)) {
            return $this->getAttributeValue($key);
        }
        if ($this->hasProperty($key)) {
            return $this->getPropertyValue($key);
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
            if ($this->hasProperty($attribute->getName())) {
                $xmlElement = $this->getProperty($attribute->getName());
                $xmlElement[0] = $attribute->getValue();
            } else {
                $this->addProperty($attribute->getName(), $attribute->getValue());
            }
        }

        return $this;
    }
}
