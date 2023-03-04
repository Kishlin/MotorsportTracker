<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchDriverQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly SearchDriverViewer $driverViewer,
    ) {
    }

    public function __invoke(SearchDriverQuery $query): SearchDriverResponse
    {
        $driverId = $this->driverViewer->search($query->name());

        if (null === $driverId) {
            throw new DriverNotFoundException();
        }

        return SearchDriverResponse::fromObject($driverId);
    }
}
