<?php


namespace Harvest\ChangeNote\Annotations;

/** @Annotation  */
abstract class ChangeValue
{
    abstract public function getValue($value);
}
