<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Country\Domain\Entity;

use Kishlin\Backend\Country\Domain\DomainEvent\CountryCreatedDomainEvent;
use Kishlin\Backend\Country\Domain\Entity\Country;
use Kishlin\Backend\Country\Domain\ValueObject\CountryCode;
use Kishlin\Backend\Country\Domain\ValueObject\CountryId;
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
        $code = 'fr';

        $entity = Country::create(new CountryId($id), new CountryCode($code));

        self::assertItRecordedDomainEvents($entity, new CountryCreatedDomainEvent(new CountryId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($code, $entity->code());
    }
}
