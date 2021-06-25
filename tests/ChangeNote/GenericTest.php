<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeTypes;
use Harvest\ChangeNote\ChangeParser;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
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
             * @ChangeTypes\PropertyName(name="Name")
             * @ChangeTypes\Generic
             */
            public $name = 'foo';
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

        $afterModel->name = 'bar';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertNotEmpty($changes);
    }

    public function testExpectedChangeValue()
    {
        $beforeModel = $this->getTestModel();
        $afterModel = $this->getTestModel();

        $afterModel->name = 'bar';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame($beforeModel->name, $changes[0]->from);
        self::assertSame($afterModel->name, $changes[0]->to);
    }
}
