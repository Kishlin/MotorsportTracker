<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Application\CreateRetirementIfNotExists;

use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\NullableIntValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

final readonly class CreateRetirementIfNotExistsCommand implements Command
{
    private function __construct(
        private string $entry,
        private string $reason,
        private string $type,
        private bool $dns,
        private ?int $lap
    ) {
    }

    public function entry(): UuidValueObject
    {
        return new UuidValueObject($this->entry);
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

    public function lap(): NullableIntValueObject
    {
        return new NullableIntValueObject($this->lap);
    }

    public static function fromScalars(string $entry, string $reason, string $type, bool $dns, ?int $lap): self
    {
        return new self($entry, $reason, $type, $dns, $lap);
    }
}
