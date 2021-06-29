<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithBoolLabel
{
    public $id;

    /**
     * @ChangeTypes\PropertyName(name="Test Bool")
     * @ChangeTypes\BoolLabel(true="ON", false="OFF")
     */
    public $testBool = true;
}
