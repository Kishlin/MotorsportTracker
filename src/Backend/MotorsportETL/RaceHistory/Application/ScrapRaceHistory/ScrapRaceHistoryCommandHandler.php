<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\RaceHistory\Application\ScrapRaceHistory;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\MotorsportETL\Shared\Domain\SessionWithResultListGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapRaceHistoryCommandHandler implements CommandHandler
{
    public function __construct(
        private CacheInvalidatorGateway $cacheInvalidatorGateway,
        private SessionWithResultListGateway $sessionListGateway,
        private RaceHistoryTransformer $transformer,
        private RaceHistoryExtractor $extractor,
        private Loader $loader,
    ) {
    }

    public function __invoke(ScrapRaceHistoryCommand $command): Result
    {
        $sessionList = $this->sessionListGateway->find($command->eventFilter());

        if (empty($sessionList)) {
            return FailResult::withCode(ScrapRaceHistoryFailures::NO_SESSIONS);
        }

        foreach ($sessionList as $session) {
            $this->scrapRaceHistoryForSession($command, $session);
        }

        return OkResult::create();
    }

    private function scrapRaceHistoryForSession(ScrapRaceHistoryCommand $command, SessionIdentity $session): void
    {
        if ($command->cacheMustBeInvalidated()) {
            $this->cacheInvalidatorGateway->invalidate(Context::RACE_HISTORY->value, $session->ref());
        }

        $rawHistories = $this->extractor->extract($session);

        if (empty($rawHistories)) {
            return;
        }

        $transformed = $this->transformer->transform($session, $rawHistories);

        foreach ($transformed as $entity) {
            $this->loader->load($entity);
        }
    }
}
