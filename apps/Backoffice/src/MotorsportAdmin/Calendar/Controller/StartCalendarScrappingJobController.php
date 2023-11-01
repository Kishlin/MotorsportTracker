<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportAdmin\Calendar\Controller;

use Kishlin\Backend\MotorsportTask\Job\Application\RecordJob\RecordJobCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/{series}/{year}',
    name: 'calendar_scrap',
    requirements: [
        'series' => '^[^/]+$',
        'year'   => '^\d{4}$',
    ],
    methods: [Request::METHOD_POST],
)]
final class StartCalendarScrappingJobController extends AbstractController
{
    public function __invoke(
        CommandBus $commandBus,
        string $series,
        int $year,
    ): JsonResponse {
        $uuid = $commandBus->execute(RecordJobCommand::scrapCalendar($series, $year));
        assert($uuid instanceof UuidValueObject);

        return new JsonResponse(['uuid' => $uuid->value()], Response::HTTP_ACCEPTED);
    }
}
