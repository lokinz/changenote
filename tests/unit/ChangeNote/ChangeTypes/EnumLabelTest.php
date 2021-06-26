<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeTypes;
use Harvest\ChangeNote\ChangeParser;
use Harvest\Enums\SendStatus;
use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use Harvest\Tests\unit\ChangeNote\ChangeParserTest;
use PHPUnit\Framework\TestCase;

class EnumLabelTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel()
    {
        return new class {
            /**
             * @ChangeTypes\PropertyName(name="Test Bool")
             * @ChangeTypes\EnumLabel(enumClass="Harvest\Enums\SendStatus")
             */
            public $testEnum;

            public function __construct()
            {
                $this->testEnum = SendStatus::IDLE();
            }
        };
    }

    public function testParsesEnumObject()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = SendStatus::PENDING();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame($afterModel->testEnum->getName(), $changes[0]->to);
    }

    public function testParsesEnumName()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = SendStatus::PENDING()->getName();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame($afterModel->testEnum, $changes[0]->to);
    }

    public function testParsesEnumValue()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->testEnum = SendStatus::PENDING()->getValue();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->testEnum->getName(), $changes[0]->from);
        self::assertSame(SendStatus::make($afterModel->testEnum)->getName(), $changes[0]->to);
    }
}
