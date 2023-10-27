<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Jobs\Controller;

use Kishlin\Backend\MotorsportAdmin\Jobs\Application\JobStatus\JobStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{job}',
    name: 'job_status',
    requirements: [
        'job' => '^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$',
    ],
    methods: [Request::METHOD_GET],
)]
final class JobStatusController extends AbstractController
{
    public function __invoke(
        JobStatusService $jobStatusService,
        string $job,
    ): JsonResponse {
        $content = $jobStatusService->status($job);

        if (null === $content) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($content->data());
    }
}
