<?php


namespace Harvest\Tests\unit\ChangeNote;


use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithDecortedAndNotDecortedProperties;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ChangeParserTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel(): ModelWithDecortedAndNotDecortedProperties
    {
        return new ModelWithDecortedAndNotDecortedProperties();
    }

    public function testGetsPropertyName(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->decorated = 'Beth Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertSame('Get Schwifty!', $changes[0]->name);
    }

    public function testThrowsExceptionWhenComparingDifferentClassTypes(): void
    {
        $model = $this->getNewTestModel();
        $wrongModel = new stdClass();

        $this->expectException(InvalidArgumentException::class);

        $this->changeParser->getChanges($model, $wrongModel);
    }

    public function testSameValueDoesNotCreateChange(): void
    {
        $model = $this->getNewTestModel();

        $changes = $this->changeParser->getChanges($model, $model);

        self::assertEmpty($changes, 'Changes were detected even though none were made');
    }

    public function testChangedValueCreatesChange(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->decorated = 'Summer Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertNotEmpty($changes, 'No changes were detected even though changes were made');
    }

    public function testNonDecoratedDoesNotCreateChanges(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->notDecorated = 'Jerry Smith';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertEmpty($changes, 'No decorated properties should not create a change');
    }

    public function testCreatesMultipleChanges(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->decorated = 'Jessica';
        $afterModel->secondDecorated = 'Squanchy';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertGreaterThanOrEqual(2, count($changes));
    }
}
