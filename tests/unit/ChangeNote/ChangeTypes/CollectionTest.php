<?php

namespace Harvest\Tests\unit\ChangeNote\ChangeTypes;

use Harvest\ChangeNote\CollectionChange;
use Harvest\Tests\unit\ChangeNote\ChangeNoteParserSetup;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithCollections;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\ModelWithGeneric;
use Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures\TestEnum;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    use ChangeNoteParserSetup;

    public function getNewTestModel(): ModelWithCollections
    {
        return new ModelWithCollections();
    }

    public function testReturnsCollectionChangeType(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $addedModel = new ModelWithGeneric();
        $addedModel->name = 'Added Item';

        $afterModel->genericCollection[] = $addedModel;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertInstanceOf(CollectionChange::class, $changes[0]);
    }

    public function testGetsAddedItemToExistingCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $addedModel = new ModelWithGeneric();
        $addedModel->id = 2;
        $addedModel->name = 'Added Item';

        $afterModel->genericCollection[] = $addedModel;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->added, 'One item should be added');

        self::assertSame($addedModel->name, $collectionChange->added[0]->name);
    }

    public function testGetsRemovedItemFromExistingCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $removedModel = $afterModel->genericCollection[0];

        unset($afterModel->genericCollection[0]);

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->removed, 'One item should be removed');

        self::assertSame($removedModel->name, $collectionChange->removed[0]->name);
    }

    public function testGetsChangedItemFromExistingCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->genericCollection[0]->name = 'I was changed';

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->changes, 'One item should have changed');

        self::assertSame($afterModel->genericCollection[0]->name, $collectionChange->changes[0]->to);
    }

    public function testGetsAddedItemsToEmptyCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $item1 = new ModelWithGeneric();
        $item1->name = 'Added Item 1';

        $item2 = new ModelWithGeneric();
        $item2->name = 'Added Item 2';

        $afterModel->emptyCollection[] = $item1;
        $afterModel->emptyCollection[] = $item2;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(2, $collectionChange->added, 'Two items should be added');

        self::assertSame($item1->name, $collectionChange->added[0]->name);
        self::assertSame($item2->name, $collectionChange->added[1]->name);
    }

    public function testGetsChangedItemsUsingNewModelsInEmptyCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $loadedModel = new ModelWithGeneric();
        $loadedModel->id = 99;

        $changedModel = new ModelWithGeneric();
        $changedModel->id = 99;
        $changedModel->name = 'Name Changed';

        $beforeModel->emptyCollection[0] = $loadedModel;
        $afterModel->emptyCollection[0] = $changedModel;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->changes, 'One item should have changed');

        self::assertSame($loadedModel->name, $collectionChange->changes[0]->from);
        self::assertSame($changedModel->name, $collectionChange->changes[0]->to);
    }

    public function testGetsChangedNonGenericItemFromExistingCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $afterModel->enumCollection[0]->testEnum = TestEnum::DONE()->getName();

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->changes, 'One item should have changed');

        self::assertSame($afterModel->enumCollection[0]->testEnum, $collectionChange->changes[0]->to);
    }

    public function testGetsChangesInNestedCollection(): void
    {
        $beforeModel = $this->getNewTestModel();
        $afterModel = $this->getNewTestModel();

        $collectionModel1 = new ModelWithCollections();
        $collectionModel1->id = 88;
        $collectionModel1->emptyCollection[0] = new ModelWithGeneric();
        $collectionModel1->emptyCollection[0]->id = 42;

        $beforeModel->emptyCollection[0] = $collectionModel1;

        $collectionModel2 = new ModelWithCollections();
        $collectionModel2->id = 88;
        $collectionModel2->emptyCollection[0] = new ModelWithGeneric();
        $collectionModel2->emptyCollection[0]->id = 42;
        $collectionModel2->emptyCollection[0]->name = 'Changed Name';

        $afterModel->emptyCollection[0] = $collectionModel2;

        $changes = $this->changeParser->getChanges($beforeModel, $afterModel);

        self::assertCount(1, $changes, 'One change should be detected');

        /** @var CollectionChange $collectionChange */
        $collectionChange = $changes[0];

        self::assertCount(1, $collectionChange->changes, 'One collection change should be detected');

        self::assertInstanceOf(CollectionChange::class, $collectionChange->changes[0], 'Change should be another collection change');

        $modelChanges = $collectionChange->changes[0]->changes;

        self::assertSame($collectionModel2->emptyCollection[0]->name, $modelChanges[0]->to);

    }
}
