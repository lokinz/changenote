<?php


namespace Harvest\ChangeNote\ChangeTypes;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
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
