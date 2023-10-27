<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Seasons\Controller;

use Kishlin\Apps\Backoffice\MotorsportAdmin\Shared\Controller\NotFoundOrContentResponseTrait;
use Kishlin\Backend\MotorsportAdmin\Seasons\Application\ListSeasons\ListSeasonsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{series}',
    name: 'seasons_list',
    requirements: [
        'series' => '^[^/]+$',
    ],
    methods: [Request::METHOD_GET],
)]
final class ListSeasonsController extends AbstractController
{
    use NotFoundOrContentResponseTrait;

    public function __invoke(
        ListSeasonsService $listSeasonsService,
        string $series,
    ): JsonResponse {
        $content = $listSeasonsService->all($series);

        return $this->notFoundOrContent($content);
    }
}
