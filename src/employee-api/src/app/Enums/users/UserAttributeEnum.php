<?php

namespace App\Enums\users;

enum UserAttributeEnum: string
{
    case USER_NAME = 'name';
    case USER_AGE = 'age';
    case USER_GENDER = 'gender';

    /**
     * @param string $value
     * @return bool
     */
    public function valueIsValid(string $value): bool
    {
        return match ($this->name){
            UserAttributeEnum::USER_NAME->name => !empty($value),
            UserAttributeEnum::USER_AGE->name => is_numeric($value) && $value > 0,
            UserAttributeEnum::USER_GENDER->name => boolval(Gender::tryFrom($value)),
            default => false
        };
    }
}
