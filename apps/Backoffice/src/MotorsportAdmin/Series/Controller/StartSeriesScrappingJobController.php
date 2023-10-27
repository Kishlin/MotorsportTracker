<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Series\Controller;

use Kishlin\Backend\MotorsportTask\Job\Application\RecordJob\RecordJobCommand;
use Kishlin\Backend\MotorsportTask\Series\Application\Scrap\ScrapSeriesTask;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '',
    name: 'series_scrap',
    methods: [Request::METHOD_POST],
)]
final class StartSeriesScrappingJobController extends AbstractController
{
    public function __invoke(
        CommandBus $commandBus,
        TaskBus $taskBus,
    ): JsonResponse {
        $uuid = $commandBus->execute(RecordJobCommand::scrapSeriesJob());

        assert($uuid instanceof UuidValueObject);
        $taskBus->execute(ScrapSeriesTask::forJob($uuid->value()));

        return new JsonResponse(['uuid' => $uuid->value()], Response::HTTP_ACCEPTED);
    }
}
