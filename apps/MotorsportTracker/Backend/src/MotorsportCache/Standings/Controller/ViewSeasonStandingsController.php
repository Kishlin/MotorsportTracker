<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportCache\Standings\Controller;

use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\SeasonStandingsNotFoundException;
use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\ViewSeasonStandingsQuery;
use Kishlin\Backend\MotorsportCache\Standing\Application\ViewSeasonStandings\ViewSeasonStandingsResponse;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{championshipSlug}/{year}',
    name: 'season_standings',
    requirements: [
        'championshipSlug' => '[\w\-]+',
        'year'             => '[\d]{4}',
    ],
    methods: [Request::METHOD_GET],
)]
final class ViewSeasonStandingsController extends AbstractController
{
    public function __invoke(QueryBus $queryBus, string $championshipSlug, int $year): JsonResponse
    {
        try {
            /** @var ViewSeasonStandingsResponse $seasonStandingsResponse */
            $seasonStandingsResponse = $queryBus->ask(ViewSeasonStandingsQuery::fromScalars($championshipSlug, $year));
        } catch (SeasonStandingsNotFoundException) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($seasonStandingsResponse->view()->toArray());
    }
}
