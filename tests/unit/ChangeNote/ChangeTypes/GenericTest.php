<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;


use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithGeneric;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel(): ModelWithGeneric
    {
        return new ModelWithGeneric();
    }

    public function testExpectedChangeValue(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->name = 'bar';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->name, $changes[0]->from);
        self::assertSame($afterModel->name, $changes[0]->to);
    }
}
