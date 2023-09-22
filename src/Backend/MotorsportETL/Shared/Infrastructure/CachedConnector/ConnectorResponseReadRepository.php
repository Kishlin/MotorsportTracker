<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ClientRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ReadRepository;

final readonly class ConnectorResponseReadRepository extends ReadRepository implements ClientRepositoryInterface
{
    public function findResponse(Context $context, string $parametersKey): ?string
    {
        $qb = $this->createQueryBuilder();

        $qb
            ->select('response')
            ->from($context->value)
            ->where($qb->expr()->eq('key', ':key'))
            ->withParam('key', $parametersKey)
            ->limit(1)
        ;

        $ret = $this->connection->execute($qb->buildQuery())->fetchOne();

        if (false === is_string($ret)) {
            return null;
        }

        return $ret;
    }
}
