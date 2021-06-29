<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;

use Harvest\ChangeNote\ChangeTypes;

class ModelWithBoolLabel
{

    /**
     * @ChangeTypes\PropertyName(name="Test Bool")
     * @ChangeTypes\BoolLabel(true="ON", false="OFF")
     */
    public $testBool = true;
}
