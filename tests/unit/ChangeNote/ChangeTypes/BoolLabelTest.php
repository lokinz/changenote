<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Harvest\ChangeNote\ChangeTypes;
use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use PHPUnit\Framework\TestCase;

class BoolLabelTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel()
    {
        return new class {
            /**
             * @ChangeTypes\PropertyName(name="Test Bool")
             * @ChangeTypes\BoolLabel(true="ON", false="OFF")
             */
            public $testBool = true;
        };
    }

    public function testExpectedChangeValue()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testBool = false;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame('ON', $changes[0]->from);
        self::assertSame('OFF', $changes[0]->to);
    }
}
