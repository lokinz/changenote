<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Harvest\Enums\SendStatus;
use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithEnum;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\TestEnum;
use PHPUnit\Framework\TestCase;

class EnumLabelTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel(): ModelWithEnum
    {
        return new ModelWithEnum();
    }

    public function testParsesEnumObject(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = TestEnum::PENDING();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame($afterModel->testEnum->getName(), $changes[0]->to);
    }

    public function testParsesEnumName(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = TestEnum::PENDING()->getName();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame($afterModel->testEnum, $changes[0]->to);
    }

    public function testParsesEnumValue(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = TestEnum::PENDING()->getValue();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame(SendStatus::make($afterModel->testEnum)->getName(), $changes[0]->to);
    }
}
