<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class FindEntryForSessionAndNumberQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly FindEntryForSessionAndNumberGateway $gateway,
    ) {
    }

    public function __invoke(FindEntryForSessionAndNumberQuery $query): FindEntryForSessionAndNumberResponse
    {
        $id = $this->gateway->findForSessionAndNumber($query->session(), $query->number());

        if (null === $id) {
            throw new EntryNotFoundException();
        }

        return FindEntryForSessionAndNumberResponse::forId($id);
    }
}
