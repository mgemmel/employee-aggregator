<?php
declare(strict_types=1);

namespace App\Services\Users;

use App\Enums\users\EmployeeAttributeEnum;
use App\Models\users\AttributeModel;
use InvalidArgumentException;

class EmployeesAttributesService
{

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
        foreach ($attributes as $attribute) {
            $validAttribute = EmployeeAttributeEnum::tryFrom($attribute->getName());
            if (!$validAttribute) {
                throw new InvalidArgumentException($attribute->getName() . ' is invalid');
            }
            $valueIsValid = $validAttribute->valueIsValid($attribute->getValue());
            if (!$valueIsValid) {
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
        foreach (EmployeeAttributeEnum::cases() as $attributeEnum) {
            if (!$attributeEnum->isRequired()){
                continue;
            }
            $exists = false;
            /** @var AttributeModel $attribute */
            foreach ($attributes as $attribute) {
                if ($attribute->getName() == $attributeEnum->value) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                throw new InvalidArgumentException('Argument ' . $attributeEnum->value . ' is required');
            }
        }
    }

    /**
     * @return array
     */
    public function getAttributesConfig(): array
    {
        return array_map(fn(EmployeeAttributeEnum $attributeEnum) => [
            'name' => $attributeEnum->value,
            'type' => $attributeEnum->getType(),
            'required' => $attributeEnum->isRequired()
        ], EmployeeAttributeEnum::cases());
    }
}
