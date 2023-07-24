<?php

namespace App\Models\users;


use App\Enums\users\EmployeeAttributeEnum;
use JsonSerializable;
use SimpleXMLElement;

/**
 * @property int $id
 */
class EmployeeXmlModel extends AbstractXmlModel implements JsonSerializable
{

    /**
     * @param SimpleXMLElement $simpleXMLElement
     */
    public function __construct(public SimpleXMLElement $simpleXMLElement)
    {
        parent::__construct($simpleXMLElement);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $attributes = [];
        foreach (EmployeeAttributeEnum::cases() as $attributeEnum) {
            $attributes[] = [
                'type' => $attributeEnum->value,
                'value' => (string)$this->{$attributeEnum->value}
            ];
        }

        return [
            'id' => (int)$this->id,
            'attributes' => $attributes,
        ];
    }
}
