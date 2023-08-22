<?php

declare(strict_types=1);

namespace Kishlin\Backend\Client\Infrastructure\EventSubscriber;

use Kishlin\Backend\Client\Domain\Event\ClientRequestEvent;
use Kishlin\Backend\Persistence\Core\Connection\Connection;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventSubscriber;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ClientRepository;
use Psr\Log\LoggerInterface;

final class HydrateResultOnRequest extends ClientRepository implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger,
        protected Connection $connection,
    ) {
        parent::__construct($this->connection);
    }

    public function __invoke(ClientRequestEvent $event): void
    {
        $storeKey = implode('_', $event->key());

        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('response')
            ->from($event->topic())
            ->where($qb->expr()->eq('key', ':key'))
            ->withParam('key', $storeKey)
            ->limit(1)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();
        if (null === $ret) {
            return;
        }

        assert(is_string($ret));

        $decoded = base64_decode($ret, true);
        assert(false !== $decoded);

        $uncompressed = gzuncompress($decoded);
        assert(false !== $uncompressed);

        $event->setResponse($uncompressed);

        $this->logger->info("Hydrated event with response from cache database {$event->topic()} {$storeKey}");
    }
}
