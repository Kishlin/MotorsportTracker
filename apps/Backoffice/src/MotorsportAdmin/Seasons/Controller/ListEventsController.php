<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Seasons\Controller;

use Kishlin\Apps\Backoffice\MotorsportAdmin\Shared\Controller\NotFoundOrContentResponseTrait;
use Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListEvents\ListEventsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{series}/{year}',
    name: 'events_list',
    requirements: [
        'series' => '^[^/]+$',
        'year'   => '^\d{4}$',
    ],
    methods: [Request::METHOD_GET],
)]
final class ListEventsController extends AbstractController
{
    use NotFoundOrContentResponseTrait;

    public function __invoke(
        ListEventsService $listSeasonsService,
        string $series,
        int $year,
    ): JsonResponse {
        $content = $listSeasonsService->forSeries(urldecode($series), $year);

        return $this->notFoundOrContent($content);
    }
}
