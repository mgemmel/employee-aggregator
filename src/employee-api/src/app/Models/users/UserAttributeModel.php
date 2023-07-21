<?php
declare(strict_types=1);

namespace App\Models\users;

use App\Enums\users\UserAttributeEnum;

class UserAttributeModel
{
	private string $name;
    private string $type;
    private string $value;

    /**
     * @param UserAttributeEnum $attributeEnum
     * @param string $value
     */
    public function __construct(UserAttributeEnum $attributeEnum, string $value)
    {
        $this->name = $attributeEnum->value;
        $this->type = $attributeEnum->getType();
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}
