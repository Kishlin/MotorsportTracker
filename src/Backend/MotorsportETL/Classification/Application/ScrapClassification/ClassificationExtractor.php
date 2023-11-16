<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Application\ScrapClassification;

use Kishlin\Backend\MotorsportETL\Shared\Domain\ValueObject\SessionIdentity;

interface ClassificationExtractor
{
    public function extract(SessionIdentity $session): string;
}
