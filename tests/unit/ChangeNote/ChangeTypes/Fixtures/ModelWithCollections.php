<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithCollections
{
    public $id;

    /**
     * @ChangeTypes\PropertyName(name="Generic Collection")
     * @ChangeTypes\Collection(idKey="id")
     */
    public $genericCollection = [];

    /**
     * @ChangeTypes\PropertyName(name="Empty Collection")
     * @ChangeTypes\Collection(idKey="id")
     */
    public $emptyCollection = [];

    /**
     * @ChangeTypes\PropertyName(name="Enum Collection")
     * @ChangeTypes\Collection(idKey="id")
     */
    public $enumCollection = [];

    /**
     * @ChangeTypes\PropertyName(name="Generic Collection With Incorrect IdKey")
     * @ChangeTypes\Collection(idKey="badKey")
     */
    public $genericCollectionWithIncorrectIdKey = [];

    public function __construct()
    {
        $this->genericCollection[0] = new ModelWithGeneric();
        $this->genericCollection[0]->id = 1;

        $this->enumCollection[0] = new ModelWithEnum();
        $this->enumCollection[0]->id = 1;

        $this->genericCollectionWithIncorrectIdKey[0] = new ModelWithGeneric();
        $this->genericCollectionWithIncorrectIdKey[0]->id = 1;
    }


}
