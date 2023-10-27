<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Seasons\Controller;

use Kishlin\Backend\MotorsportTask\Job\Application\RecordJob\RecordJobCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Task\TaskBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{series}',
    name: 'seasons_scrap',
    requirements: [
        'series' => '^[^/]+$',
    ],
    methods: [Request::METHOD_POST],
)]
final class StartSeasonsScrappingJobController extends AbstractController
{
    public function __invoke(
        CommandBus $commandBus,
        TaskBus $taskBus,
        string $series,
    ): JsonResponse {
        $uuid = $commandBus->execute(RecordJobCommand::scrapSeasons($series));
        assert($uuid instanceof UuidValueObject);

        return new JsonResponse(['uuid' => $uuid->value()], Response::HTTP_ACCEPTED);
    }
}
