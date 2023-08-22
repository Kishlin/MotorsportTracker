<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportCache\Shared;

use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\GetSeasonEventIdListQuery;
use Kishlin\Backend\MotorsportTracker\Event\Application\GetSeasonEventIdList\GetSeasonEventIdListResponse;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

abstract class AbstractEventCommandUsingSymfony extends AbstractSeasonCommandUsingSymfony
{
    private const OPTION_EVENT = 'event';

    abstract protected function commandName(): string;

    abstract protected function commandDescription(): string;

    abstract protected function executeForEvent(SymfonyStyle $ui, string $series, int $year, string $event): void;

    abstract protected function onError(SymfonyStyle $ui, Throwable $throwable): void;

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(self::OPTION_EVENT, null, InputOption::VALUE_OPTIONAL, 'The event filter. Leave empty to sync the whole season.', false);
    }

    protected function executeForSeason(SymfonyStyle $ui, InputInterface $input, string $series, int $year): void
    {
        $eventFilter = $this->getEventFilterFromCommandOption($input);

        $response = $this->queryBus->ask(GetSeasonEventIdListQuery::fromScalars($series, $year, $eventFilter));
        assert($response instanceof GetSeasonEventIdListResponse);

        if (empty($response->eventIdList()->idList())) {
            $ui->info("No events for season {$series} {$year}");

            return;
        }

        $ui->progressStart(count($response->eventIdList()->idList()));

        foreach ($response->eventIdList()->idList() as $id) {
            $ui->progressAdvance();
            $this->executeForEvent($ui, $series, $year, $id);
        }

        $ui->progressFinish();

        $ui->success("Finished for season {$series} {$year}");
    }

    private function getEventFilterFromCommandOption(InputInterface $input): ?string
    {
        $event = $input->getOption(self::OPTION_EVENT);
        if (empty($event)) {
            $event = null;
        }

        assert(null === $event || is_string($event));

        return $event;
    }
}
