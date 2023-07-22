<?php

namespace App\Models\users;


use App\Enums\users\UserAttributeEnum;
use JsonSerializable;
use SimpleXMLElement;

/**
 * @property int $id
 */
class UserXmlModel extends AbstractXmlModel implements JsonSerializable
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
        foreach (UserAttributeEnum::cases() as $attributeEnum) {
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
