<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Results;

use Kishlin\Apps\Backoffice\MotorsportCache\Shared\AbstractEventCommandUsingSymfony;
use Kishlin\Backend\MotorsportCache\Result\Application\UpdateEventResultsCache\UpdateEventResultsCacheCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class SyncEventResultsCommandUsingSymfony extends AbstractEventCommandUsingSymfony
{
    protected function commandName(): string
    {
        return 'kishlin:motorsport-cache:results:compute';
    }

    protected function commandDescription(): string
    {
        return 'Compute results for all events in the list.';
    }

    protected function executeForEvent(SymfonyStyle $ui, string $series, int $year, string $event): void
    {
        $this->commandBus->execute(UpdateEventResultsCacheCommand::fromScalars($event));
    }

    protected function onError(SymfonyStyle $ui, Throwable $throwable): void
    {
        $ui->error("Failed to sync results: {$throwable->getMessage()}");
    }
}
