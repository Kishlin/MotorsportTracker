<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Driver\Application\SearchDriver;

use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class SearchDriverResponse implements Response
{
    private function __construct(
        private readonly UuidValueObject $driverId,
    ) {
    }

    public function driverId(): UuidValueObject
    {
        return $this->driverId;
    }

    public static function fromObject(UuidValueObject $driverId): self
    {
        return new self($driverId);
    }
}
