<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithDecortedAndNotDecortedProperties
{
    public $id;
    /**
     * @ChangeTypes\PropertyName(name="Get Schwifty!")
     * @ChangeTypes\Generic
     */
    public $decorated = 'Rick Sanchez';

    public $notDecorated = 'Morty Smith';

    /**
     * @ChangeTypes\PropertyName(name="Prime Squancher")
     * @ChangeTypes\Generic
     */
    public $secondDecorated = 'Bird Person';
}
