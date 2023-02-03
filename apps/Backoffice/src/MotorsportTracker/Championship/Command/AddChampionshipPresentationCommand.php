<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionshipPresentation\CreateChampionshipPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ChampionshipPOPO;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsQuery;
use Kishlin\Backend\MotorsportTracker\Championship\Application\ViewAllChampionships\ViewAllChampionshipsResponse;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipPresentationId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class AddChampionshipPresentationCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:championship-presentation:add';

    private const ARGUMENT_ICON = 'icon';
    private const QUESTION_ICON = "Please enter an icon for the championship presentation:\n";

    private const ARGUMENT_COLOR = 'color';
    private const QUESTION_COLOR = "Please enter a color for the championship presentation:\n";

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new championship presentation.')
            ->addArgument(self::ARGUMENT_ICON, InputArgument::OPTIONAL, 'The icon of the championship presentation')
            ->addArgument(self::ARGUMENT_COLOR, InputArgument::OPTIONAL, 'The color of the championship presentation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        /** @var ViewAllChampionshipsResponse $championshipsResponse */
        $championshipsResponse = $this->queryBus->ask(new ViewAllChampionshipsQuery());

        if (empty($championshipsResponse->championships())) {
            $ui->warning('There are no championship available. Create one first.');

            return Command::INVALID;
        }

        $selectedChampionshipId = $this->selectAChampionshipId($input, $output, $championshipsResponse);

        $icon  = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_ICON, self::QUESTION_ICON);
        $color = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_COLOR, self::QUESTION_COLOR);

        try {
            /** @var ChampionshipPresentationId $uuid */
            $uuid = $this->commandBus->execute(
                CreateChampionshipPresentationCommand::fromScalars($selectedChampionshipId, $icon, $color),
            );
        } catch (Throwable) {
            $ui->error('Failed to save the championship presentation.');

            return Command::FAILURE;
        }

        $ui->success("Championship Presentation Created: {$uuid}");

        return Command::SUCCESS;
    }

    private function selectAChampionshipId(
        InputInterface $input,
        OutputInterface $output,
        ViewAllChampionshipsResponse $championshipsResponse,
    ): string {
        /** @var ChampionshipPOPO $selectedChampionship */
        $selectedChampionship = $this->selectItemInList(
            $input,
            $output,
            'Please select a championship.',
            $championshipsResponse->championships(),
            'Championship %s is invalid.',
        );

        return $selectedChampionship->id();
    }
}
