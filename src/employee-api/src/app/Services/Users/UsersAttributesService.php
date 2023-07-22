<?php
declare(strict_types=1);

namespace App\Services\Users;

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
     * @return AttributeModel[]
     */
    public function validateUserAttributes(array $newAttributes): array
    {
        $validAttributes = [];
        foreach (UserAttributeEnum::cases() as $attributeEnum) {
            $value = $newAttributes[$attributeEnum->value] ?? null;
            if (!is_null($value)) {
                $valid = match ($attributeEnum->getType()) {
                    'string' => is_string($value),
                    'int' => is_integer($value) && $value > 0,
                    default => false,
                };
                if (!$valid) {
                    throw new InvalidArgumentException($attributeEnum->value . ' is invalid');
                }
                $validAttributes[] = new AttributeModel($attributeEnum->value, (string)$value);
            }
        }

        return $validAttributes;
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
