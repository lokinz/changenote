<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithGeneric
{
    /**
     * @ChangeTypes\PropertyName(name="Name")
     * @ChangeTypes\Generic
     */
    public $name = 'foo';
}
