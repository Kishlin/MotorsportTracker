<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\MotorsportTracker\Event;

use Exception;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SaveEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Application\CreateEventSessionIfNotExists\SearchEventSessionGateway;
use Kishlin\Backend\MotorsportTracker\Event\Domain\Entity\EventSession;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\Utils\AbstractRepositorySpy;

/**
 * @property EventSession[] $objects
 *
 * @method EventSession[]    all()
 * @method null|EventSession get(UuidValueObject $id)
 * @method EventSession      safeGet(UuidValueObject $id)
 */
final class EventSessionRepositorySpy extends AbstractRepositorySpy implements SaveEventSessionGateway, SearchEventSessionGateway
{
    /**
     * @throws Exception
     */
    public function save(EventSession $eventSession): void
    {
        $this->objects[$eventSession->id()->value()] = $eventSession;
    }

    public function search(string $slug): ?UuidValueObject
    {
        foreach ($this->objects as $eventSession) {
            if ($slug === $eventSession->slug()->value()) {
                return $eventSession->id();
            }
        }

        return null;
    }
}
