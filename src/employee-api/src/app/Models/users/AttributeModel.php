<?php
declare(strict_types=1);

namespace App\Models\users;

class AttributeModel
{

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(private readonly string $name, private readonly string $value)
    {
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
    public function getValue(): string
    {
        return $this->value;
    }

}
