<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Shared\Command;

use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\SearchSeason\SearchSeasonResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

trait CommandRequiringSeasonIdTrait
{
    protected function addSeasonIdArguments(): static
    {
        return $this
            ->addArgument('championship', InputArgument::OPTIONAL, 'A keyword to find the championship')
            ->addArgument('year', InputArgument::OPTIONAL, 'The year of the season')
        ;
    }

    /**
     * @throws Throwable
     */
    private function findSeasonId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $championship = $this->stringFromArgumentsOrPrompt(
            $input,
            $output,
            'championship',
            "Please enter a keyword to find the championship:\n",
        );

        $year = $this->intFromArgumentsOrPrompt(
            $input,
            $output,
            'year',
            "Please enter the year of the season:\n",
        );

        try {
            /** @var SearchSeasonResponse $seasonResponse */
            $seasonResponse = $this->queryBus->ask(SearchSeasonQuery::fromScalars($championship, $year));
        } catch (Throwable $e) {
            $ui->error("Failed to find the season with keyword {$championship} for year {$year}.");

            throw $e;
        }

        return $seasonResponse->seasonId()->value();
    }
}
