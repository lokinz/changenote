<?php


namespace Harvest\Tests\unit\ChangeNote;


use Harvest\ChangeNote\ChangeTypes;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChangeParserTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel()
    {
        return new class {
            /**
             * @ChangeTypes\PropertyName(name="Get Schwifty!")
             * @ChangeTypes\Generic
             */
            public $deccorated = 'Rick Sanchez';

            public $notDecorated = 'Morty Smith';

            /**
             * @ChangeTypes\PropertyName(name="Prime Squancher")
             * @ChangeTypes\Generic
             */
            public $secondDecorated = 'Bird Person';
        };
    }

    public function testGetsPropertyName()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->deccorated = 'Beth Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame('Get Schwifty!', $changes[0]->name);
    }

    public function testThrowsExceptionWhenComparingDifferentClassTypes()
    {
        $model = $this->getNewTestModel();
        $wrongModel = new stdClass();

        self::expectException(InvalidArgumentException::class);
        $changes = $this->changeParser->getChanges($model, $wrongModel);
    }

    public function testSameValueDoesNotCreateChange()
    {
        $model = $this->getNewTestModel();

        $changes = $this->changeParser->getChanges($model, $model);

        self::assertEmpty($changes, 'Changes were detected even though none were made');
    }

    public function testChangedValueCreatesChange()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->deccorated = 'Summer Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertNotEmpty($changes, 'No changes were detected even though changes were made');
    }

    public function testNonDecoratedDoesNotCreateChanges()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->notDecorated = 'Jerry Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertEmpty($changes, 'No decorated properties should not create a change');
    }

    public function testCreatesMultipleChanges()
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->deccorated = 'Jessica';
        $afterModel->secondDecorated = 'Squanchy';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertGreaterThanOrEqual(2, count($changes));
    }
}
