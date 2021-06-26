<?php


namespace Harvest\ChangeNote\ChangeTypes;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
abstract class ChangeValue
{
    abstract public function getValue($value);
}
