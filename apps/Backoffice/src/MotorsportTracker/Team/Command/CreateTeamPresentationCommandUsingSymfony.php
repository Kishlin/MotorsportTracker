<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Team\Command;

use Kishlin\Backend\MotorsportTracker\Team\Application\CreateTeamPresentation\CreateTeamPresentationCommand;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamQuery;
use Kishlin\Backend\MotorsportTracker\Team\Application\SearchTeam\SearchTeamResponse;
use Kishlin\Backend\MotorsportTracker\Team\Domain\ValueObject\TeamPresentationId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateTeamPresentationCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:team-presentation:add';

    private const ARGUMENT_TEAM = 'team';
    private const QUESTION_TEAM = "Please enter a keyword to find the team:\n";

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the team:\n";

    private const ARGUMENT_IMAGE = 'image';
    private const QUESTION_IMAGE = "Please enter an image for the team:\n";

    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new team.')
            ->addArgument(self::ARGUMENT_TEAM, InputArgument::OPTIONAL, 'A keyword to find the team')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the team')
            ->addArgument(self::ARGUMENT_IMAGE, InputArgument::OPTIONAL, 'The image of the team')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        try {
            $teamId = $this->findTeamId($input, $output, $ui);
        } catch (Throwable) {
            return Command::FAILURE;
        }

        $name  = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $image = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_IMAGE, self::QUESTION_IMAGE);

        try {
            /** @var TeamPresentationId $uuid */
            $uuid = $this->commandBus->execute(CreateTeamPresentationCommand::fromScalars($teamId, $name, $image));
        } catch (Throwable) {
            $ui->error('Failed to create the Team Presentation with these parameters.');

            return Command::FAILURE;
        }

        $ui->success("Team Presentation Created: {$uuid}");

        return Command::SUCCESS;
    }

    /**
     * @throws Throwable
     */
    private function findTeamId(InputInterface $input, OutputInterface $output, SymfonyStyle $ui): string
    {
        $team = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_TEAM, self::QUESTION_TEAM);

        try {
            /** @var SearchTeamResponse $teamResponse */
            $teamResponse = $this->queryBus->ask(SearchTeamQuery::fromScalars($team));
        } catch (Throwable $e) {
            $ui->error("Failed to find the team with keyword {$team}.");

            throw $e;
        }

        return $teamResponse->teamId()->value();
    }
}
