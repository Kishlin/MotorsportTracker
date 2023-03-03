<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Country\Domain\Entity;

use Kishlin\Backend\Country\Domain\Entity\Country;
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

        $entity = Country::create(new UuidValueObject($id), new StringValueObject($code), new StringValueObject($name));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($code, $entity->code());
        self::assertValueObjectSame($name, $entity->name());
    }
}
