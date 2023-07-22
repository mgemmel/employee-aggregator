<?php
declare(strict_types=1);

namespace App\Services\Users;

use App\Enums\users\Gender;
use App\Enums\users\UserAttributeEnum;
use App\Models\users\AttributeModel;
use InvalidArgumentException;

class UsersAttributesService
{
    const REQUIRED_ATTRIBUTES = [
        UserAttributeEnum::USER_NAME
    ];

    /**
     * @param array $newAttributes
     * @return array
     */
    public function parseAttributes(array $newAttributes): array
    {
        $parsedAttributes = [];
        foreach ($newAttributes as $name => $value) {
            $parsedAttributes[] = new AttributeModel($name, (string)$value);
        }

        return $parsedAttributes;
    }

    /**
     * @param AttributeModel[] $attributes
     * @return void
     */
    public function validateAttributes(array $attributes): void
    {
        foreach ($attributes as $attribute){
            $validAttribute = UserAttributeEnum::tryFrom($attribute->getName());
            if (!$validAttribute){
                throw new InvalidArgumentException($attribute->getName() . ' is invalid');
            }
            $valueIsValid = $validAttribute->valueIsValid($attribute->getValue());
            if (!$valueIsValid){
                throw new InvalidArgumentException($attribute->getName() . ' has invalid value');
            }
        }
    }

    /**
     * @param array $attributes
     * @return void
     */
    public function validateRequiredAttributes(array $attributes): void
    {
        /** @var UserAttributeEnum $attributeEnum */
        foreach (self::REQUIRED_ATTRIBUTES as $attributeEnum) {
            $exists = false;
            /** @var AttributeModel $attribute */
            foreach ($attributes as $attribute) {
                if ($attribute->getName() == $attributeEnum->value) {
                    $exists = true;
                }
            }
            if (!$exists) {
                throw new \InvalidArgumentException('Argument ' . $attributeEnum->value . ' is required');
            }
        }
    }
}
