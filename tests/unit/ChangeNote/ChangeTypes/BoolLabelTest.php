<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithBoolLabel;
use PHPUnit\Framework\TestCase;

class BoolLabelTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel(): ModelWithBoolLabel
    {
        return new ModelWIthBoolLabel();
    }

    public function testExpectedChangeValue(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testBool = false;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame('ON', $changes[0]->from);
        self::assertSame('OFF', $changes[0]->to);
    }
}
