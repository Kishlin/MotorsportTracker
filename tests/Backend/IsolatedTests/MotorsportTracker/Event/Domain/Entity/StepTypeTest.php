<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\MotorsportTracker\Event\Domain\Entity;

use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\StepTypeCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeId;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\StepTypeLabel;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\StepType
 */
final class StepTypeTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $id    = '1f686dd4-1ca3-42cf-82fb-ab3e5f20bbf4';
        $label = 'step';

        $entity = StepType::create(new StepTypeId($id), new StepTypeLabel($label));

        self::assertItRecordedDomainEvents($entity, new StepTypeCreatedDomainEvent(new StepTypeId($id)));

        self::assertValueObjectSame($id, $entity->id());
        self::assertValueObjectSame($label, $entity->label());
    }
}
