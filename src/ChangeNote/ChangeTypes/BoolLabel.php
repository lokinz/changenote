<?php


namespace Harvest\ChangeNote\ChangeTypes;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class BoolLabel extends ChangeValue
{

    private $true;
    private $false;

    public function __construct(array $values)
    {
        $this->false = $values['false'] ?? 'FALSE';
        $this->true = $values['true'] ?? 'TRUE';
    }

    public function getValue($value): string
    {
        $state = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        return $state ? $this->true : $this->false;
    }
}
