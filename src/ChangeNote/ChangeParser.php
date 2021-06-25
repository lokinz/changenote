<?php


namespace Harvest\ChangeNote;


use Doctrine\Common\Annotations\AnnotationReader;
use Harvest\ChangeNote\Annotations\ChangeValue;
use Harvest\ChangeNote\Annotations\PropertyName;
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
     * @throws ReflectionException
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

        if ($before->{$propertyName} === $after->{$propertyName}) {
            return null;
        }

        $change = new Change();

        $changeName = $this->getChangeName($property);
        if (null === $changeName) {
            return null;
        }

        $change->name = $changeName->getName();

        $changeValue = $this->getChangeValue($property);

        var_dump($changeValue);

        if (null === $changeValue) {
            return $change;
        }

        var_dump($changeValue);

        $change->from = $changeValue->getValue($before->{$propertyName});
        $change->to = $changeValue->getValue($after->{$propertyName});

        return $change;
    }

    private function getChangeName(ReflectionProperty $reflection): ?PropertyName
    {
        return $this->annotationReader->getPropertyAnnotation($reflection, PropertyName::class);
    }

    private function getChangeValue(ReflectionProperty $reflection): ?ChangeValue
    {
        return $this->annotationReader->getPropertyAnnotation($reflection, ChangeValue::class);
    }
}
