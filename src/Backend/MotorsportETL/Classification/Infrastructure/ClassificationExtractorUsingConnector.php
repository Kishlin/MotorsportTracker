<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Infrastructure;

use Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification\ClassificationExtractor;
use Kishlin\Backend\MotorsportETL\Shared\Application\Connector;
use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;

final readonly class ClassificationExtractorUsingConnector implements ClassificationExtractor
{
    private const URL = 'https://api.motorsportstats.com/widgets/1.0.0/sessions/%s/classification';

    public function __construct(
        private Connector $connector,
    ) {}

    public function extract(SessionIdentity $session): string
    {
        return $this->connector->fetch(
            self::URL,
            [$session->ref()],
        );
    }
}
