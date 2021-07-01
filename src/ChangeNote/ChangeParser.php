<?php


namespace Harvest\ChangeNote;


use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\ChangeTypes;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ChangeParser
{

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @throws InvalidArgumentException|ReflectionException
     */
    public function getChanges($before, $after): array
    {
        if (get_class($before) !== get_class($after)) {
            throw new InvalidArgumentException('objects my be of the same type');
        }

        $changes = [];

        $reflection = new ReflectionClass($before);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $change = $this->processChange($property, $before, $after);
            if (null !== $change) {
                $changes[] = $change;
            }
        }

        return $changes;
    }

    /**
     * @throws ReflectionException
     */
    private function processChange(ReflectionProperty $property, $before, $after): ?Change
    {
        $propertyName = $property->getName();

        if ($before->{$propertyName} == $after->{$propertyName}) { //non strict for array comparison
            return null;
        }

        $changeValue = $this->getChangeValue($property);

        if (is_a($changeValue, ChangeTypes\Collection::class)) {
            return $this->processCollection($property, $before, $after);
        }

        $change = new ChangeNote();

        $changeName = $this->getChangeName($property);
        if (null === $changeName) {
            return null;
        }

        $change->name = $changeName->getName();

        if (null === $changeValue) {
            return $change;
        }

        $change->from = $changeValue->getValue($before->{$propertyName});
        $change->to = $changeValue->getValue($after->{$propertyName});

        return $change;
    }

    private function getChangeName(ReflectionProperty $reflection): ?ChangeTypes\PropertyName
    {
        return $this->annotationReader
            ->getPropertyAnnotation($reflection, ChangeTypes\PropertyName::class);
    }

    private function getChangeValue(ReflectionProperty $reflection): ?ChangeTypes\ChangeValue
    {
        return $this->annotationReader
            ->getPropertyAnnotation($reflection, ChangeTypes\ChangeValue::class);
    }

    /**
     * @throws ReflectionException
     */
    private function processCollection(ReflectionProperty $property, $before, $after): ?CollectionChange
    {
        $change = new CollectionChange();

        $changeName = $this->getChangeName($property);
        if (null === $changeName) {
            return null;
        }
        $change->name = $changeName->getName();

        $propertyName = $property->getName();

        $beforeCollection = $before->{$propertyName};
        $afterCollection = $after->{$propertyName};

        $idKey = $this->getChangeValue($property)->getValue($after);
        $this->validateIdKey($idKey, $beforeCollection, $afterCollection);

        $change->added = $this->getCollectionAdded($idKey, $beforeCollection, $afterCollection);

        $keyedBeforeCollection = $this->collectionByIdKey($idKey, $beforeCollection);
        $keyedAfterCollection = $this->collectionByIdKey($idKey, $afterCollection);

        $change->removed = $this->getCollectionRemoved($keyedBeforeCollection, $afterCollection);

        $change->changes = $this->getCollectionChanges($idKey, $keyedBeforeCollection, $keyedAfterCollection);

        return $change;
    }

    private function getCollectionAdded($idKey, $before, $after): array
    {
        $added = [];

        foreach ($after as $k => $item) {
            if (null === $item->{$idKey}) {
                $added[] = $item;
                unset($after[$k]);
            }
        }

        $added = array_merge($added, array_diff_key($after, $before));
        return array_values($added);
    }

    private function getCollectionRemoved($before, $after): array
    {
        $removed = array_diff_key($before, $after);
        return array_values($removed);
    }

    private function validateIdKey($idKey, $beforeCollection, $afterCollection): void
    {
        if (count($beforeCollection) > 0) {
            $this->testForIdKey($idKey, $beforeCollection[0]);
            return;
        }

        if (count($afterCollection) > 0) {
            $this->testForIdKey($idKey, $afterCollection[0]);
        }
    }

    private function testForIdKey($idKey, $object): void
    {
        if (!property_exists($object, $idKey)) {
            $message = sprintf(
                'IdKey "%s" not found on object "%s"',
                $idKey,
                get_class($object)
            );

            throw new InvalidArgumentException($message);
        }
    }

    private function collectionByIdKey($idKey, $collection): array
    {
        $keyedCollection = [];

        foreach ($collection as $item) {
            if(null === $item->{$idKey}){
                continue;
            }
            $keyedCollection[$item->{$idKey}] = $item;
        }

        return $keyedCollection;
    }

    /**
     * @throws ReflectionException
     */
    private function getCollectionChanges($idKey, $beforeCollection,  $afterCollection): array
    {
        $changes = [];

        foreach ($afterCollection as $item) {
            if (!isset($beforeCollection[$item->{$idKey}])) {
                continue;
            }

            foreach ($this->getChanges($beforeCollection[$item->{$idKey}], $item) as $changeItem) {
                $changes[] = $changeItem;
            }
        }

        return $changes;
    }

}
