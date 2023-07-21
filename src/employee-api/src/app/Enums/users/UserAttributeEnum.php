<?php

namespace App\Enums\users;

enum UserAttributeEnum: string
{
    case USER_NAME = 'name';
    case USER_AGE = 'age';
    case USER_GENDER = 'gender';

    /**
     * @return string
     */
    public function getType(): string
    {
        return match ($this->value) {
            UserAttributeEnum::USER_NAME->value, UserAttributeEnum::USER_GENDER->value => 'string',
            UserAttributeEnum::USER_AGE->value => 'int',
        };
    }
}
