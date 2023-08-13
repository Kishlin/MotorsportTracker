<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportCache\EventGraph\Application;

use Kishlin\Backend\MotorsportCache\EventGraph\Domain\Entity\Graph;
use Kishlin\Backend\Shared\Domain\Cache\CacheItem;
use Kishlin\Backend\Shared\Domain\Cache\CachePersister;

final readonly class GraphDataSaverUsingCacheItem implements GraphDataSaver
{
    public function __construct(
        private CachePersister $cachePersister,
    ) {
    }

    public function save(string $event, Graph $graph): void
    {
        assert($graph instanceof CacheItem);

        $this->cachePersister->save($graph, ['event' => $event]);
    }
}
