<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithCollections
{
    public $id;

    /**
     * @ChangeTypes\PropertyName(name="Generic Collection")
     * @ChangeTypes\Collection()
     */
    public $genericCollection = [];

    /**
     * @ChangeTypes\PropertyName(name="Empty Collection")
     * @ChangeTypes\Collection()
     */
    public $emptyCollection = [];

    /**
     * @ChangeTypes\PropertyName(name="Enum Collection")
     * @ChangeTypes\Collection()
     */
    public $enumCollection = [];

    public function __construct()
    {
        $this->genericCollection[0] = new ModelWithGeneric();
        $this->genericCollection[0]->id = 1;

        $this->enumCollection[0] = new ModelWithEnum();
        $this->enumCollection[0]->id = 1;
    }
}
