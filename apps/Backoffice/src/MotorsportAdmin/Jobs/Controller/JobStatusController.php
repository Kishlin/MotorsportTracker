<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Jobs\Controller;

use Kishlin\Apps\Backoffice\MotorsportAdmin\Shared\Controller\NotFoundOrContentResponseTrait;
use Kishlin\Backend\MotorsportAdmin\Jobs\Application\JobStatus\JobStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    use NotFoundOrContentResponseTrait;

    public function __invoke(
        JobStatusService $jobStatusService,
        string $job,
    ): JsonResponse {
        $content = $jobStatusService->status($job);

        return $this->notFoundOrContent($content);
    }
}
