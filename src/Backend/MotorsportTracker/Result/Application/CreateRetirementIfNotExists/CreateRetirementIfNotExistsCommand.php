<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\PositiveIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final class CreateRetirementIfNotExistsCommand implements Command
{
    private function __construct(
        private readonly string $session,
        private readonly int $number,
        private readonly string $reason,
        private readonly string $type,
        private readonly bool $dns,
        private readonly int $lap
    ) {
    }

    public function session(): UuidValueObject
    {
        return new UuidValueObject($this->session);
    }

    public function number(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->number);
    }

    public function reason(): StringValueObject
    {
        return new StringValueObject($this->reason);
    }

    public function type(): StringValueObject
    {
        return new StringValueObject($this->type);
    }

    public function dns(): BoolValueObject
    {
        return new BoolValueObject($this->dns);
    }

    public function lap(): PositiveIntValueObject
    {
        return new PositiveIntValueObject($this->lap);
    }

    public static function fromScalars(string $session, int $number, string $reason, string $type, bool $dns, int $lap): self
    {
        return new self($session, $number, $reason, $type, $dns, $lap);
    }
}
