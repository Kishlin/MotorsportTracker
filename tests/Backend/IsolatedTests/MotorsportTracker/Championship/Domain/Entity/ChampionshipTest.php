<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Championship\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Championship\Domain\DomainEvent\ChampionshipCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableStringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableUuidValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Championship\Domain\Entity\Series
 */
final class ChampionshipTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id    = 'bec0ec9f-e814-4dd6-bc77-d08e6ca17023';
        $name  = 'championship';
        $short = 'short';
        $code  = 'slug';
        $ref   = 'd6ffcebd-1d69-4cee-b5b0-874c5d69e39f';

        $entity = Series::create(
            new UuidValueObject($id),
            new StringValueObject($name),
            new NullableStringValueObject($short),
            new StringValueObject($code),
            new NullableUuidValueObject($ref),
        );

        self::assertItRecordedDomainEvents($entity, new ChampionshipCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($name, $entity->name());
        self::assertValueObjectSame($short, $entity->shortName());
        self::assertValueObjectSame($code, $entity->shortCode());
        self::assertValueObjectSame($ref, $entity->ref());
    }
}
