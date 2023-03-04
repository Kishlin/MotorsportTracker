<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableDateTimeValueObject;
use ReflectionException;

class NullableDateTimeValueObjectType extends DateTimeImmutableType
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
     * @param ?DateTimeImmutable $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): NullableDateTimeValueObject
    {
        $className = $this->mappedClass();

        /** @var ?DateTimeImmutable $dateTimeImmutable */
        $dateTimeImmutable = parent::convertToPHPValue($value, $platform);

        return new $className($dateTimeImmutable);
    }

    /**
     * @param NullableDateTimeValueObject $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $databaseValue = parent::convertToDatabaseValue($value->value(), $platform);

        assert(null === $databaseValue || is_string($databaseValue));

        return $databaseValue;
    }

    /**
     * @return class-string<NullableDateTimeValueObject>
     */
    protected function mappedClass(): string
    {
        return NullableDateTimeValueObject::class;
    }
}
