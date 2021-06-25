<?php


namespace Harvest\ChangeNote\ChangeTypes;

/** @Annotation */
class Generic extends ChangeValue
{
    public function getValue($value)
    {
        return $value;
    }
}
