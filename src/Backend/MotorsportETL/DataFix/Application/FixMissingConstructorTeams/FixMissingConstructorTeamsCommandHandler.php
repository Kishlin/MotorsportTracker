<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\DataFix\Application\FixMissingConstructorTeams;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Psr\Log\LoggerInterface;

final readonly class FixMissingConstructorTeamsCommandHandler implements CommandHandler
{
    public function __construct(
        private FixMissingConstructorTeamsGateway $gateway,
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function __invoke(FixMissingConstructorTeamsCommand $command): void
    {
        $this->gateway->fixMissingData();

        $this->logger?->info('Fixed missing constructor-team relations.');
    }
}
