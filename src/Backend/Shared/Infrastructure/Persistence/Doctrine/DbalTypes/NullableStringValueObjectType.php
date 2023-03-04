<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use ReflectionException;

class NullableStringValueObjectType extends StringType
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
     * @param ?string $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): NullableStringValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param NullableStringValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value->value();
    }

    /**
     * @return class-string<NullableStringValueObject>
     */
    protected function mappedClass(): string
    {
        return NullableStringValueObject::class;
    }
}
