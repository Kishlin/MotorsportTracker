<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportTracker\Standing\Controller;

use Kishlin\Apps\MotorsportTracker\Backend\Shared\Exception\ErrorJsonResponseBuilder;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonResponse;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SeasonNotFoundException;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Standing\Application\ViewTeamStandingsForSeason\ViewTeamStandingsForSeasonResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/teams/{championship}/{year}',
    name: 'standings_teams',
    requirements: ['year' => '\d{4}'],
    methods: [Request::METHOD_GET],
)]
final class TeamStandingsForSeasonController extends AbstractController
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

        /** @var ViewTeamStandingsForSeasonResponse $standingsResponse */
        $standingsResponse = $queryBus->ask(
            ViewTeamStandingsForSeasonQuery::fromScalars($seasonResponse->seasonId()->value()),
        );

        return new JsonResponse($standingsResponse->teamStandingsView()->toArray());
    }
}
