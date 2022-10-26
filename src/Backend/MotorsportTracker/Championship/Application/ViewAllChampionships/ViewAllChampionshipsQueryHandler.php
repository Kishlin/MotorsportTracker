<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class ViewAllChampionshipsQueryHandler implements QueryHandler
{
    public function __construct(
        private ViewAllChampionshipsGateway $gateway,
    ) {
    }

    public function __invoke(ViewAllChampionshipsQuery $query): ViewAllChampionshipsResponse
    {
        return ViewAllChampionshipsResponse::fromChampionshipList(
            $this->gateway->viewAllChampionships(),
        );
    }
}
