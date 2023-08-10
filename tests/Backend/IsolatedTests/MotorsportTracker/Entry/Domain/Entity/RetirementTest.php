<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Entry\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Result\Domain\DomainEvent\RetirementCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Result\Domain\Entity\Retirement;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @coversNothing
 */
final class RetirementTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id     = '1c8dab0a-3d13-4463-9b6d-b5e0d0ed4fef';
        $entry  = '7ba56fe5-b557-456c-b01c-e50a396fe45f';
        $reason = 'reason';
        $type   = 'type';
        $lap    = 35;

        $entity = Retirement::create(
            new UuidValueObject($id),
            new UuidValueObject($entry),
            new StringValueObject($reason),
            new StringValueObject($type),
            new BoolValueObject(true),
            new NullableIntValueObject($lap),
        );

        self::assertItRecordedDomainEvents($entity, new RetirementCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($entry, $entity->entry());
        self::assertValueObjectSame($reason, $entity->reason());
        self::assertValueObjectSame($type, $entity->type());
        self::assertValueObjectSame(true, $entity->dns());
        self::assertValueObjectSame($lap, $entity->lap());
    }
}
