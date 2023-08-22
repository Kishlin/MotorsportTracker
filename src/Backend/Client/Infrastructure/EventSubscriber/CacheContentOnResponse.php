<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Infrastructure\EventSubscriber;

use Kishlin\Backend\Client\Domain\Event\ClientResponseEvent;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ClientRepository;
use Psr\Log\LoggerInterface;

final class CacheContentOnResponse extends ClientRepository implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
        protected Connection $connection,
    ) {
        parent::__construct($this->connection);
    }

    public function __invoke(ClientResponseEvent $event): void
    {
        $storeKey = implode('_', $event->key());

        $this->logger->info("Saving response for request {$event->topic()} {$storeKey}");

        $compressed = gzcompress($event->response(), 9);
        assert(false !== $compressed);

        $encoded = base64_encode($compressed);

        $data = [
            'key'      => $storeKey,
            'response' => $encoded,
        ];

        $this->connection->insert($event->topic(), $data);
    }
}
