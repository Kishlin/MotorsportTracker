<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;
use ReflectionException;

class JsonValueObjectType extends JsonType
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
     * @param array<int|string, mixed> $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): JsonValueObject
    {
        $className = $this->mappedClass();

        return new $className(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param JsonValueObject $value
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
     * @return class-string<JsonValueObject>
     */
    protected function mappedClass(): string
    {
        return JsonValueObject::class;
    }
}
