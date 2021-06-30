<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithNoId
{
    /**
     * @ChangeTypes\PropertyName(name="Name")
     * @ChangeTypes\Generic
     */
    public $name = 'No Id';
}
