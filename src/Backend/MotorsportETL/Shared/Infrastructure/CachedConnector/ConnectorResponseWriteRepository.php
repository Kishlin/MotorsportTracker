<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Shared\Infrastructure\CachedConnector;

use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\ClientRepositoryInterface;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Repository\WriteRepository;

final readonly class ConnectorResponseWriteRepository extends WriteRepository implements ClientRepositoryInterface
{
    public function save(Context $context, string $parametersKey, string $response): void
    {
        $this->connection->insert(
            table: $context->value,
            data: [
                'key'      => $parametersKey,
                'response' => $response,
            ],
        );
    }
}
