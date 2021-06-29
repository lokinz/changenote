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

    private function processChange(ReflectionProperty $property, $before, $after): ?Change
    {
        $propertyName = $property->getName();

        if ($before->{$propertyName} == $after->{$propertyName}) { //non strict for array comparison
            return null;
        }

        $changeValue = $this->getChangeValue($property);

        if (is_a($changeValue, ChangeTypes\Collection::class)){
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

        $change->added = $this->getAdded($idKey, $beforeCollection, $afterCollection);
        $change->removed = $this->getRemoved($beforeCollection, $afterCollection);

        $possibleChanges = array_diff_key($afterCollection, $change->added);
        $beforeKeys = [];

        foreach ($beforeCollection as $item) {
            $beforeKeys[$item->{$idKey}] = $item;
        }

        foreach ($possibleChanges as $item){
            if(!isset($beforeKeys[$item->{$idKey}])) {
                continue;
            }

            foreach ($this->getChanges($beforeKeys[$item->{$idKey}], $item) as $changeItem){
                $change->changes[] = $changeItem;
            }
        }

        return $change;
    }

    private function getAdded($idKey, $before, $after): array
    {
       $added = [];

        foreach ($after as $k => $item){
            if(null === $item->{$idKey}){
                $added[] = $item;
                unset($after[$k]);
            }
        }

        $added = array_merge($added, array_diff_key($after, $before));
        return array_values($added);
    }

    private function getRemoved($before, $after): array
    {
        $removed = array_diff_key($before, $after);
        return array_values($removed);
    }
}
