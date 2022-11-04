<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Championship\Command;

use Kishlin\Backend\MotorsportTracker\Championship\Application\CreateChampionship\CreateChampionshipCommand;
use Kishlin\Backend\MotorsportTracker\Championship\Domain\ValueObject\ChampionshipId;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class AddChampionshipCommand extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:championship:add';

    private const ARGUMENT_NAME = 'name';
    private const QUESTION_NAME = "Please enter a name for the championship:\n";

    private const ARGUMENT_SLUG = 'slug';
    private const QUESTION_SLUG = "Please enter a slug for the championship:\n";

    public function __construct(
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds a new championship.')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::OPTIONAL, 'The name of the championship')
            ->addArgument(self::ARGUMENT_SLUG, InputArgument::OPTIONAL, 'The slug of the championship')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        $name = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_NAME, self::QUESTION_NAME);
        $slug = $this->stringFromArgumentsOrPrompt($input, $output, self::ARGUMENT_SLUG, self::QUESTION_SLUG);

        try {
            /** @var ChampionshipId $uuid */
            $uuid = $this->commandBus->execute(CreateChampionshipCommand::fromScalars($name, $slug));
        } catch (Throwable) {
            $ui->error('A Championship with that name and/or slug already exists.');

            return Command::FAILURE;
        }

        $ui->success("Championship Created: {$uuid}");

        return Command::SUCCESS;
    }
}
