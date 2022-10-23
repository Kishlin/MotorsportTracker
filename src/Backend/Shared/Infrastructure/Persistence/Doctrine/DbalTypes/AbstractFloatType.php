<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\FloatValueObject;
use ReflectionException;

abstract class AbstractFloatType extends FloatType
{
    /**
     * @throws ReflectionException
     */
    public function getName(): string
    {
        $shortClassName = Tools::shortClassName($this->mappedClass());

        return Tools::fromPascalToSnakeCase($shortClassName);
    }

    /**
     * @param float $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): FloatValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param FloatValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): float
    {
        return $value->value();
    }

    /**
     * @return class-string<FloatValueObject>
     */
    abstract protected function mappedClass(): string;
}
