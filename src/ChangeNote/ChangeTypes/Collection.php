<?php


namespace Harvest\ChangeNote\ChangeTypes;

/** @Annotation */
class Collection extends ChangeValue
{
    private $idKey;

    public function __construct(array $values)
    {
        $this->idKey = $values['idKey'] ?? 'id';
    }

    public function getValue($value)
    {
        return $this->idKey;
    }
}
