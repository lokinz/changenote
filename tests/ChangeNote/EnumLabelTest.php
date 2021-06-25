<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeTypes;
use Harvest\ChangeNote\ChangeParser;
use Harvest\Enums\SendStatus;
use PHPUnit\Framework\TestCase;

class EnumLabelTest extends TestCase
{

    /**
     * @var ChangeParser
     */
    private $changeParser;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->changeParser = new ChangeParser(new AnnotationReader());
        parent::__construct($name, $data, $dataName);
    }

    public function getTestModel()
    {
        return new class {
            /**
             * @ChangeTypes\PropertyName(name="Test Bool")
             * @ChangeTypes\EnumLabel(enumClass="Harvest\Enums\SendStatus")
             */
            public $testEnum = 0;
        };
    }

    public function testValueDoesNotChange()
    {
        $beforeModel = $this->getTestModel();
        $afterModel = $this->getTestModel();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertEmpty($changes);
    }

    public function testValueChanges()
    {
        $beforeModel = $this->getTestModel();
        $afterModel = $this->getTestModel();

        $afterModel->testEnum = 2;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertNotEmpty($changes);
    }

    public function testExpectedChangeValue()
    {
        $beforeModel = $this->getTestModel();
        $afterModel = $this->getTestModel();

        $afterModel->testEnum = 1;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame(SendStatus::make($beforeModel->testEnum)->getName(), $changes[0]->from);
        self::assertSame(SendStatus::make($afterModel->testEnum)->getName(), $changes[0]->to);
    }
}
