<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Car\Application\SearchCar;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryHandler;

final class SearchCarQueryHandler implements QueryHandler
{
    public function __construct(
        private SearchCarViewer $carViewer,
    ) {
    }

    public function __invoke(SearchCarQuery $query): SearchCarResponse
    {
        $carId = $this->carViewer->search($query->number(), $query->team(), $query->championship(), $query->year());

        if (null === $carId) {
            throw new CarNotfoundException();
        }

        return SearchCarResponse::fromScalars($carId);
    }
}
