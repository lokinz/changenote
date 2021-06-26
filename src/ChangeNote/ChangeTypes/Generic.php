<?php


namespace Harvest\ChangeNote\ChangeTypes;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Generic extends ChangeValue
{
    public function getValue($value)
    {
        return $value;
    }
}
