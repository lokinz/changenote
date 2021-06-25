<?php


namespace Harvest\ChangeNote\Annotations;

/** @Annotation  */
class PropertyName
{
    private $name;

    public function __construct(array $values)
    {
        $this->name = $values['name'] ?? 'undefined';
    }

    public function getName(): string
    {
        return $this->name;
    }
}
