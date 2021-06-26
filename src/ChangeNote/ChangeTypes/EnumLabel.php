<?php


namespace Harvest\ChangeNote\ChangeTypes;

use Spatie\Enum\Enum;
use Throwable;

/** @Annotation */
class EnumLabel extends ChangeValue
{
    /** @var Enum */
    private $enumClass;

    public function __construct(array $values)
    {
        $this->enumClass = $values['enumClass'];
    }

    public function getValue($value): string
    {
        try {
            /** @var Enum $enum */
            $enum = $this->enumClass::make($value);
        } catch (Throwable $e) {
            return 'Unknown';
        }

        return $enum->getName();
    }
}
