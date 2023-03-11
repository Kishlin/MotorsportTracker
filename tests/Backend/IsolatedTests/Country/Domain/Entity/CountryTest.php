<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Country\Domain\Entity;

use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Country\Domain\Entity\Country
 */
final class CountryTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id   = '8888d266-1cb4-4248-9c2b-140f42169c6e';
        $name = 'France';
        $code = 'fr';
        $ref  = '0ea935cb-5cbf-44d2-9e12-c315594cbe32';

        $entity = Country::create(
            new UuidValueObject($id),
            new StringValueObject($code),
            new StringValueObject($name),
            new NullableUuidValueObject($ref),
        );

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($code, $entity->code());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
