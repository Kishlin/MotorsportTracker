<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\Event\Infrastructure\Repository\SyncEventCache;

use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\EventCachedAlreadyExistException;
use Kishlin\Backend\MotorsportCache\Event\Application\SyncEventCache\Gateway\SaveEventCacheGateway;
use Kishlin\Backend\MotorsportCache\Event\Domain\Entity\EventCached;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\CacheRepository;
use PDOException;

final class SaveEventCacheRepository extends CacheRepository implements SaveEventCacheGateway
{
    private const DUPLICATE_ERROR_CODE = 23505;

    public function save(EventCached $eventCached): void
    {
        try {
            $this->persist($eventCached);
        } catch (PDOException $e) {
            if (self::DUPLICATE_ERROR_CODE === (int) $e->getCode()) {
                throw new EventCachedAlreadyExistException();
            }

            throw $e;
        }
    }
}
