<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification;

use Kishlin\Backend\MotorsportETL\Shared\Application\Loader\Loader;
use Kishlin\Backend\MotorsportETL\Shared\Domain\CacheInvalidatorGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\Context;
use Kishlin\Backend\MotorsportETL\Shared\Domain\SessionWithResultListGateway;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Result\FailResult;
use Kishlin\Backend\Shared\Domain\Result\OkResult;
use Kishlin\Backend\Shared\Domain\Result\Result;

final readonly class ScrapClassificationCommandHandler implements CommandHandler
{
    public function __construct(
        private CacheInvalidatorGateway $cacheInvalidatorGateway,
        private SessionWithResultListGateway $sessionListGateway,
        private ClassificationTransformer $transformer,
        private ClassificationExtractor $extractor,
        private Loader $loader,
    ) {}

    public function __invoke(ScrapClassificationCommand $command): Result
    {
        $sessionList = $this->sessionListGateway->find($command->eventFilter());

        if (empty($sessionList)) {
            return FailResult::withCode(ScrapClassificationFailures::NO_SESSIONS);
        }

        foreach ($sessionList as $session) {
            $this->scrapClassificationsForSession($command, $session);
        }

        return OkResult::create();
    }

    private function scrapClassificationsForSession(ScrapClassificationCommand $command, SessionIdentity $session): void
    {
        if ($command->cacheMustBeInvalidated()) {
            $this->cacheInvalidatorGateway->invalidate(Context::CLASSIFICATION->value, $session->ref());
        }

        $rawClassifications = $this->extractor->extract($session);

        $transformed = $this->transformer->transform($session, $rawClassifications);

        foreach ($transformed as $entity) {
            $this->loader->load($entity);
        }
    }
}
