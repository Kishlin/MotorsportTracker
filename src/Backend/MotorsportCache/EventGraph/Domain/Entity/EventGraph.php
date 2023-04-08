<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity;

use JsonException;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\DomainEvent\EventGraphCreatedDomainEvent;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphOrder;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Enum\EventGraphType;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphDataValueObject;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphOrderValueObject;
use Kishlin\Backend\MotorsportCache\EventGraph\Domain\ValueObject\EventGraphTypeValueObject;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class EventGraph extends AggregateRoot
{
    private function __construct(
        private readonly UuidValueObject $id,
        private readonly UuidValueObject $event,
        private readonly EventGraphOrderValueObject $order,
        private readonly EventGraphTypeValueObject $type,
        private readonly EventGraphDataValueObject $data,
    ) {
    }

    public static function lapByLap(
        UuidValueObject $id,
        UuidValueObject $event,
        EventGraphDataValueObject $data,
    ): self {
        $order = new EventGraphOrderValueObject(EventGraphOrder::LAP_BY_LAP_PACE);
        $type  = new EventGraphTypeValueObject(EventGraphType::LAP_BY_LAP_PACE);

        $eventGraph = new self($id, $event, $order, $type, $data);

        $eventGraph->record(new EventGraphCreatedDomainEvent($id));

        return $eventGraph;
    }

    /**
     * @internal only use to get a test object
     */
    public static function instance(
        UuidValueObject $id,
        UuidValueObject $event,
        EventGraphOrderValueObject $order,
        EventGraphTypeValueObject $type,
        EventGraphDataValueObject $data,
    ): self {
        return new self($id, $event, $order, $type, $data);
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public function event(): UuidValueObject
    {
        return $this->event;
    }

    public function order(): EventGraphOrderValueObject
    {
        return $this->order;
    }

    public function type(): EventGraphTypeValueObject
    {
        return $this->type;
    }

    public function data(): EventGraphDataValueObject
    {
        return $this->data;
    }

    /**
     * @throws JsonException
     */
    public function mappedData(): array
    {
        return [
            'id'    => $this->id->value(),
            'event' => $this->event->value(),
            'order' => $this->order->asInt(),
            'type'  => $this->type->asString(),
            'data'  => $this->data->asString(),
        ];
    }
}
