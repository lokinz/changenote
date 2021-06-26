<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Harvest\ChangeNote\ChangeTypes;
use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel()
    {
        return new class {
            /**
             * @ChangeTypes\PropertyName(name="Name")
             * @ChangeTypes\Generic
             */
            public $name = 'foo';
        };
    }

    public function testExpectedChangeValue()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->name = 'bar';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->name, $changes[0]->from);
        self::assertSame($afterModel->name, $changes[0]->to);
    }
}
