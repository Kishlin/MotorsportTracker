<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Jobs\Controller;

use Kishlin\Backend\MotorsportAdmin\Jobs\Application\ListUnfinishedJobs\UnfinishedJobsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{type}',
    name: 'jobs_unfinished',
    requirements: ['type' => '[\w+_]+'],
    methods: [Request::METHOD_GET],
)]
final class ListUnfinishedJobsController extends AbstractController
{
    public function __invoke(
        UnfinishedJobsService $unfinishedJobsService,
        string $type,
    ): JsonResponse {
        return new JsonResponse(
            $unfinishedJobsService->forType($type)->data(),
        );
    }
}
