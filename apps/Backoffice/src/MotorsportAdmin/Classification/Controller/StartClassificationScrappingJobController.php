<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Classification\Controller;

use Kishlin\Backend\MotorsportTask\Job\Application\RecordJob\RecordJobCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{series}/{year}/{event}',
    name: 'classifications_scrap',
    requirements: [
        'series' => '^[^/]+$',
        'year'   => '^\d{4}$',
        'event'  => '^[^/]+$',
    ],
    methods: [Request::METHOD_POST],
)]
final class StartClassificationScrappingJobController extends AbstractController
{
    public function __invoke(
        CommandBus $commandBus,
        string $series,
        int $year,
        string $event,
    ): JsonResponse {
        $uuid = $commandBus->execute(RecordJobCommand::scrapClassifications($series, $year, $event));
        assert($uuid instanceof UuidValueObject);

        return new JsonResponse(['uuid' => $uuid->value()], Response::HTTP_ACCEPTED);
    }
}
