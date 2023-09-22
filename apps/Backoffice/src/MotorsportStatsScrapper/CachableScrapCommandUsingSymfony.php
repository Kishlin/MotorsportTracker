<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\MotorsportStatsScrapper;

use Kishlin\Backend\MotorsportETL\Shared\Application\ScrapWithCacheCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Result\Result;
use Kishlin\Backend\Tools\Infrastructure\Symfony\Command\SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

abstract class CachableScrapCommandUsingSymfony extends SymfonyCommand
{
    private const OPTION_INVALIDATE_CACHE = 'invalidate-cache';

    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();

        $this->addOption(
            self::OPTION_INVALIDATE_CACHE,
            'i',
            InputOption::VALUE_NONE,
            'Invalidate cache before scrapping',
        );
    }

    public function executeApplicationCommand(InputInterface $input, ScrapWithCacheCommand $command): Result
    {
        if (true === $input->getOption(self::OPTION_INVALIDATE_CACHE)) {
            $command->invalidateCache();
        }

        $result = $this->commandBus->execute($command);

        assert($result instanceof Result);

        return $result;
    }
}
