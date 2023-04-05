<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportCache\Event;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\SaveEventCacheGateway;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\CachedEventsJsonableView;
use Kishlin\Backend\MotorsportCache\Event\Application\ViewCachedEvents\ViewCachedEventsGateway;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property EventCached[] $objects
 *
 * @method EventCached[]    all()
 * @method null|EventCached get(UuidValueObject $id)
 * @method EventCached      safeGet(UuidValueObject $id)
 */
final class EventCachedRepositorySpy extends AbstractRepositorySpy implements SaveEventCacheGateway, ViewCachedEventsGateway
{
    public function save(EventCached $eventCached): void
    {
        $this->add($eventCached);
    }

    public function findAll(): CachedEventsJsonableView
    {
        $data = [];

        foreach ($this->objects as $eventCached) {
            $data[] = [
                'championship' => $eventCached->championship()->value(),
                'year'         => $eventCached->year()->value(),
                'event'        => $eventCached->eventSlug()->value(),
            ];
        }

        return CachedEventsJsonableView::fromData($data);
    }

    public function find(string $championship, int $year, string $event): ?EventCached
    {
        foreach ($this->objects as $eventCached) {
            if ($championship !== $eventCached->championship()->value()) {
                continue;
            }

            if ($year !== $eventCached->year()->value()) {
                continue;
            }

            if ($event !== $eventCached->eventSlug()->value()) {
                continue;
            }

            return $eventCached;
        }

        return null;
    }
}
