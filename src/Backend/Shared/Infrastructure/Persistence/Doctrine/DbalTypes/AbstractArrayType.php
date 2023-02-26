<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\ConversionException;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\ArrayValueObject;
use ReflectionException;

abstract class AbstractArrayType extends ArrayType
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
     * @param array<int|string, float|integer|string> $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ArrayValueObject
    {
        $className = $this->mappedClass();

        return new $className(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param ArrayValueObject $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        $converted = parent::convertToDatabaseValue($value->value(), $platform);

        assert(is_string($converted));

        return $converted;
    }

    /**
     * @return class-string<ArrayValueObject>
     */
    abstract protected function mappedClass(): string;
}
