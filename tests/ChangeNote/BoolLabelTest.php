<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeTypes;
use Harvest\ChangeNote\ChangeParser;
use PHPUnit\Framework\TestCase;

class BoolLabelTest extends TestCase
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
             * @ChangeTypes\BoolLabel(true="ON", false="OFF")
             */
            public $testBool = true;
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

        $afterModel->testBool = false;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertNotEmpty($changes);
    }

    public function testExpectedChangeValue()
    {
        $beforeModel = $this->getTestModel();
        $afterModel = $this->getTestModel();

        $afterModel->testBool = false;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame('ON', $changes[0]->from);
        self::assertSame('OFF', $changes[0]->to);
    }
}
