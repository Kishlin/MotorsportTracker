<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Series\Controller;

use Kishlin\Backend\MotorsportAdmin\Series\Application\ListSeries\ListSeriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '',
    name: 'series_list',
    methods: [Request::METHOD_GET],
)]
final class ListSeriesController extends AbstractController
{
    public function __invoke(
        ListSeriesService $listSeriesService,
    ): JsonResponse {
        return new JsonResponse(
            $listSeriesService->all()->data(),
        );
    }
}
