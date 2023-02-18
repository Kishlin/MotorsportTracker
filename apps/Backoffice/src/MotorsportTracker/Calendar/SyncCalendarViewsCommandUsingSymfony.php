<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportTracker\Calendar;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\MotorsportTracker\Event\Domain\DomainEvent\EventStepCreatedDomainEvent;
use Kishlin\Backend\MotorsportTracker\Event\Domain\ValueObject\EventStepId;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SyncCalendarViewsCommandUsingSymfony extends SymfonyCommand
{
    public const NAME = 'kishlin:motorsport:calendar:sync';

    private const QUERY = <<<'SQL'
SELECT es.id
FROM event_steps es
WHERE es.id NOT IN (
    SELECT cv.reference
    FROM calendar_event_step_views cv
)
SQL;


    public function __construct(
        private readonly EventDispatcher $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Adds missing event step presentations.')
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        /** @var array<array{id: string}> $result */
        $result = $this->entityManager->getConnection()->executeQuery(self::QUERY)->fetchAllAssociative();

        if (empty($result)) {
            $ui->info('There are no event steps waiting to be synced.');

            return Command::SUCCESS;
        }

        foreach ($result as $eventStep) {
            $this->eventDispatcher->dispatch(new EventStepCreatedDomainEvent(new EventStepId($eventStep['id'])));

            $ui->info("Synced presentation for event {$eventStep['id']}");
        }

        return Command::SUCCESS;
    }
}
