<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Standing\Command\PerSeason;

use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\DriverStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Entity\TeamStandingsView;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway\DriverStandingsViewsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Domain\Gateway\TeamStandingsViewsGateway;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewChampionshipSlug;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewEvents;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewId;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewStandings;
use Kishlin\Backend\MotorsportCache\Standing\Domain\ValueObject\StandingsViewYear;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractPerSeasonStandingsCommand extends SymfonyCommand
{
    public const OPTION_DRY_RUN = 'dry-run';

    public function __construct(
        private readonly UuidGenerator $uuidGenerator,
        private readonly TeamStandingsViewsGateway $teamStandingsViewsGateway,
        private readonly DriverStandingsViewsGateway $driverStandingsViewsGateway,
    ) {
        parent::__construct();
    }

    abstract protected function name(): string;

    abstract protected function championship(): string;

    abstract protected function year(): int;

    protected function configure(): void
    {
        $this
            ->setName($this->name())
            ->setDescription('Adds standings for the championship season.')
            ->addOption(self::OPTION_DRY_RUN, null, InputOption::VALUE_NONE, 'Should it be dry run only?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        /** @var bool $isDryRun */
        $isDryRun = $input->getOption(self::OPTION_DRY_RUN);

        $driversStandings = $teamsStandings = [];

        foreach ($this->results() as $result) {
            $firstDriverOfTeam = false;

            $driversStandings[$result['driver']] = [
                'name'  => $result['driver'],
                'color' => $result['color'],
                'data'  => [],
            ];

            if (false === array_key_exists($result['team'], $teamsStandings)) {
                $teamsStandings[$result['team']] = [
                    'name'  => $result['team'],
                    'color' => $result['color'],
                    'data'  => [],
                ];
                $firstDriverOfTeam = true;
            }

            $sum = 0;
            foreach ($result['points'] as $index => $points) {
                $sum += $points;

                $teamsStandings[$result['team']]['data'][$index] = false === $firstDriverOfTeam
                    ? $sum + $teamsStandings[$result['team']]['data'][$index]
                    : $sum;

                $driversStandings[$result['driver']]['data'][$index] = $sum;
            }
        }

        if ($isDryRun) {
            $this->debugDataInOutput($ui, $teamsStandings, $driversStandings);
        } else {
            $this->saveStandings($ui, $teamsStandings, $driversStandings);
        }

        return Command::SUCCESS;
    }

    /**
     * @return string[]
     */
    abstract protected function events(): array;

    /**
     * @return array<int, array{driver: string, points: int[], team: string, color: string}>
     */
    abstract protected function results(): array;

    /**
     * @param array<string, array{name: string, color: string, data: int[]}> $teamsStandings
     * @param array<string, array{name: string, color: string, data: int[]}> $driversStandings
     */
    private function debugDataInOutput(SymfonyStyle $ui, array $teamsStandings, array $driversStandings): void
    {
        $ui->info(
            array_map(
                static function ($teamStanding) {
                    $total = end($teamStanding['data']);

                    return "{$teamStanding['name']} ends with a total of {$total}.";
                },
                $teamsStandings,
            ),
        );

        $ui->info(
            array_map(
                static function ($driverStanding) {
                    $total = end($driverStanding['data']);

                    return "{$driverStanding['name']} ends with a total of {$total}.";
                },
                $driversStandings,
            ),
        );
    }

    /**
     * @param array<string, array{name: string, color: string, data: int[]}> $teamsStandings
     * @param array<string, array{name: string, color: string, data: int[]}> $driversStandings
     */
    private function saveStandings(SymfonyStyle $ui, array $teamsStandings, array $driversStandings): void
    {
        $events = $this->events();

        $teamStandingsViewId   = $this->uuidGenerator->uuid4();
        $driverStandingsViewId = $this->uuidGenerator->uuid4();

        $championship = $this->championship();
        $year         = $this->year();

        $this->teamStandingsViewsGateway->deleteIfExists($championship, $year);

        $this->teamStandingsViewsGateway->save(TeamStandingsView::instance(
            new StandingsViewId($teamStandingsViewId),
            new StandingsViewChampionshipSlug($championship),
            new StandingsViewYear($year),
            new StandingsViewEvents($events),
            new StandingsViewStandings(array_values($teamsStandings)),
        ));

        $this->driverStandingsViewsGateway->deleteIfExists($championship, $year);

        $this->driverStandingsViewsGateway->save(DriverStandingsView::instance(
            new StandingsViewId($driverStandingsViewId),
            new StandingsViewChampionshipSlug($championship),
            new StandingsViewYear($year),
            new StandingsViewEvents($events),
            new StandingsViewStandings(array_values($driversStandings)),
        ));

        $ui->success([
            "Saved TeamStandingsView: {$teamStandingsViewId}.",
            "Saved DriverStandingsView: {$driverStandingsViewId}.",
        ]);
    }
}
