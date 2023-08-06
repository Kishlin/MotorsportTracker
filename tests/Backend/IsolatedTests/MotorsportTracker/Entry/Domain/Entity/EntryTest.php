<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Entry\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\EntryCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Entry;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @coversNothing
 */
final class EntryTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id        = '057bf060-304e-4833-a00a-5dedaa0ae90b';
        $session   = 'bcf6a1b3-d5fc-4be9-93ec-b28989f81f92';
        $country   = '702dbdc5-650b-4d4a-9342-63f96778d52a';
        $driver    = '352d1d74-dfce-4d49-854d-fd7bfbd2e0dd';
        $team      = '373433eb-4dcf-4dc2-92b3-113f38906d26';
        $carNumber = 33;

        $entity = Entry::create(
            new UuidValueObject($id),
            new UuidValueObject($session),
            new UuidValueObject($country),
            new UuidValueObject($driver),
            new UuidValueObject($team),
            new PositiveIntValueObject($carNumber),
        );

        self::assertItRecordedDomainEvents($entity, new EntryCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($session, $entity->session());
        self::assertValueObjectSame($country, $entity->country());
        self::assertValueObjectSame($driver, $entity->driver());
        self::assertValueObjectSame($team, $entity->team());
        self::assertValueObjectSame($carNumber, $entity->carNumber());
    }
}
