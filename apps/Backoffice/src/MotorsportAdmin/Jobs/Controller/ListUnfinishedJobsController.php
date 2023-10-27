<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Jobs\Controller;

use Kishlin\Backend\MotorsportAdmin\Jobs\Application\ListUnfinishedJobs\UnfinishedJobsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/',
    name: 'jobs_unfinished',
    methods: [Request::METHOD_GET],
)]
final class ListUnfinishedJobsController extends AbstractController
{
    public function __invoke(
        UnfinishedJobsService $unfinishedJobsService,
    ): JsonResponse {
        return new JsonResponse(
            $unfinishedJobsService->all()->data(),
        );
    }
}
