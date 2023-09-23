<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Standing\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class ConstructorAnalyticsData implements Mapped
{
    private function __construct(
        private int $wins,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'wins' => $this->wins,
        ];
    }

    /**
     * @param array{wins: int} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['wins'],
        );
    }
}
