<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportTracker\Result\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\JsonValueObject;

/**
 * @property array{
 *     type: string,
 *     wear: string,
 *     laps: int,
 * }[] $value
 */
final class TyreDetailsValueObject extends JsonValueObject
{
    /**
     * @return array{
     *     type: string,
     *     wear: string,
     *     laps: int,
     * }[]
     */
    public function data(): array
    {
        return $this->value;
    }

    /**
     * @param array{
     *     type: string,
     *     wear: string,
     *     laps: int,
     * }[] $data
     */
    public static function fromData(array $data): self
    {
        return new self($data);
    }
}
