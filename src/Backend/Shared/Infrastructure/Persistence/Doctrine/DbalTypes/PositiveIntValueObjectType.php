<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use ReflectionException;

class PositiveIntValueObjectType extends IntegerType
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
     * @param int $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): PositiveIntValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param PositiveIntValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->value();
    }

    /**
     * @return class-string<PositiveIntValueObject>
     */
    protected function mappedClass(): string
    {
        return PositiveIntValueObject::class;
    }
}
