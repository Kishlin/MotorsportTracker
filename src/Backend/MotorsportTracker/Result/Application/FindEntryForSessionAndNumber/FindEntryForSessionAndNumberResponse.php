<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\FindEntryForSessionAndNumber;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class FindEntryForSessionAndNumberResponse implements Response
{
    private function __construct(
        private readonly UuidValueObject $id,
    ) {
    }

    public function id(): UuidValueObject
    {
        return $this->id;
    }

    public static function forId(UuidValueObject $uuid): self
    {
        return new self($uuid);
    }
}
