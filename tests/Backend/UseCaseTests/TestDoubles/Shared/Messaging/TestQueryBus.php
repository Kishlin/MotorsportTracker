<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use RuntimeException;

final readonly class TestQueryBus implements QueryBus
{
    public function __construct(
    ) {
    }

    public function ask(Query $query): ?Response
    {
        throw new RuntimeException('Unknown query type: ' . get_class($query));
    }
}
