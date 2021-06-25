<?php


namespace Harvest\ChangeNote\Annotations;

/** @Annotation */
class Generic extends ChangeValue
{
    public function getValue($value)
    {
        return $value;
    }
}
