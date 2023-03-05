<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\SessionTypeCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\SessionType
 */
final class SessionTypeTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id    = '1f686dd4-1ca3-42cf-82fb-ab3e5f20bbf4';
        $label = 'step';

        $entity = SessionType::create(new UuidValueObject($id), new StringValueObject($label));

        self::assertItRecordedDomainEvents($entity, new SessionTypeCreatedDomainEvent(new UuidValueObject($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($label, $entity->label());
    }
}
