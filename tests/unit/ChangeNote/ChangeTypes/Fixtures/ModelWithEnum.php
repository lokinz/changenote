<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithEnum
{
    /**
     * @ChangeTypes\PropertyName(name="Test Bool")
     * @ChangeTypes\EnumLabel(enumClass="Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\TestEnum")
     */
    public $testEnum;

    public function __construct()
    {
        $this->testEnum = TestEnum::IDLE();
    }
}
