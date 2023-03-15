<?php

declare(strict_types=1);

namespace Kishlin\Apps\MotorsportTracker\Backend\MotorsportTracker\Standing\Controller;

use Kishlin\Apps\MotorsportTracker\Backend\Shared\Exception\ErrorJsonResponseBuilder;
use Kishlin\Backend\MotorsportCache\Standing\Infrastructure\Persistence\Repository\TeamStandingsViewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route(
    '/teams/{championship}/{year}',
    name: 'standings_teams',
    requirements: ['year' => '\d{4}'],
    methods: [Request::METHOD_GET],
)]
final class TeamStandingsForSeasonController extends AbstractController
{
    public function __invoke(TeamStandingsViewsRepository $gateway, string $championship, int $year): Response
    {
        try {
            $standings = $gateway->findOne($championship, $year);
        } catch (Throwable) {
            return ErrorJsonResponseBuilder::new()
                ->withMessage('Season was not found with these parameters.')
                ->withCode(Response::HTTP_NOT_FOUND)
                ->build()
                ;
        }

        if (null === $standings) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'events'    => $standings->events()->value(),
            'standings' => $standings->standings()->value(),
        ]);
    }
}
