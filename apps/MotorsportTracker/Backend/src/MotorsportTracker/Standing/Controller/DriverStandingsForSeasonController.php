<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportTracker\Standing\Controller;

use Kishlin\Apps\MotorsportTracker\Backend\Shared\Exception\ErrorJsonResponseBuilder;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonResponse;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SeasonNotFoundException;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewDriverStandingsForSeason\ViewDriverStandingsForSeasonResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/drivers/{championship}/{year}',
    name: 'standings_drivers',
    requirements: ['year' => '\d{4}'],
    methods: [Request::METHOD_GET],
)]
final class DriverStandingsForSeasonController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $championship, int $year): Response
    {
        try {
            /** @var SearchSeasonResponse $seasonResponse */
            $seasonResponse = $queryBus->ask(SearchSeasonQuery::fromScalars($championship, $year));
        } catch (SeasonNotFoundException) {
            return ErrorJsonResponseBuilder::new()
                ->withMessage('Season was not found with these parameters.')
                ->withCode(Response::HTTP_NOT_FOUND)
                ->build()
            ;
        }

        /** @var ViewDriverStandingsForSeasonResponse $standingsResponse */
        $standingsResponse = $queryBus->ask(
            ViewDriverStandingsForSeasonQuery::fromScalars($seasonResponse->seasonId()->value()),
        );

        return new JsonResponse($standingsResponse->driverStandingsView()->toArray());
    }
}
