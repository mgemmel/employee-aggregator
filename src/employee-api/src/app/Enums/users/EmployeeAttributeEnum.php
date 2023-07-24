<?php

namespace App\Enums\users;

enum EmployeeAttributeEnum: string
{
    case USER_NAME = 'name';
    case USER_AGE = 'age';
    case USER_GENDER = 'gender';

    /**
     * validate attributes and their values, this method is used when employee is created or updated
     * @param string $value
     * @return bool
     */
    public function valueIsValid(string $value): bool
    {
        return match ($this->name){
            EmployeeAttributeEnum::USER_NAME->name => !empty($value),
            EmployeeAttributeEnum::USER_AGE->name => is_numeric($value) && $value > 0,
            EmployeeAttributeEnum::USER_GENDER->name => boolval(Gender::tryFrom($value)),
            default => false
        };
    }

    /**
     * return attribute type (used in FE form, to display field with correct input type)
     * @return mixed
     */
    public function getType(): mixed
    {
        return match ($this->name){
            EmployeeAttributeEnum::USER_NAME->name => 'string',
            EmployeeAttributeEnum::USER_AGE->name => 'int',
            EmployeeAttributeEnum::USER_GENDER->name => [Gender::MALE->value, Gender::FEMALE->value],
        };
    }

    /**
     * checks if attribute is required (used in FE form, to display required field)
     * @return bool
     */
    public function isRequired(): bool
    {
        return match ($this->name){
            EmployeeAttributeEnum::USER_NAME->name => true,
           	default => false
        };
    }
}
