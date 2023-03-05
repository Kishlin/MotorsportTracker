<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SaveEventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventIfNotExists\SearchEventGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\Event;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property Event[] $objects
 *
 * @method Event[]    all()
 * @method null|Event get(UuidValueObject $id)
 * @method Event      safeGet(UuidValueObject $id)
 */
final class SaveEventRepositorySpy extends AbstractRepositorySpy implements SaveEventGateway, SearchEventGateway
{
    /**
     * @throws Exception
     */
    public function save(Event $event): void
    {
        foreach ($this->objects as $saved) {
            if (false === $saved->seasonId()->equals($event->seasonId())) {
                continue;
            }

            if ($saved->name()->equals($event->name())) {
                throw new Exception();
            }

            if ($saved->index()->equals($event->index())) {
                throw new Exception();
            }
        }

        $this->objects[$event->id()->value()] = $event;
    }

    public function find(string $slug): ?UuidValueObject
    {
        foreach ($this->objects as $event) {
            if ($slug === $event->slug()->value()) {
                return $event->id();
            }
        }

        return null;
    }
}
