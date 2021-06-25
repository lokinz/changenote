<?php


namespace Harvest\ChangeNote\ChangeTypes;

/** @Annotation  */
abstract class ChangeValue
{
    abstract public function getValue($value);
}
