<?php

declare(strict_types=1);

namespace Kishlin\Backend\MotorsportETL\Classification\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\Entity\Mapped;

final readonly class ClassificationBestLap implements Mapped
{
    private function __construct(
        private ?int $bestLap,
        private ?float $bestTime,
        private ?bool $bestIsFastest,
        private ?float $bestSpeed,
    ) {
    }

    public function mappedData(): array
    {
        return [
            'best_lap'        => $this->bestLap,
            'best_time'       => $this->bestTime,
            'best_is_fastest' => $this->bestIsFastest ? 1 : 0,
            'best_speed'      => $this->bestSpeed,
        ];
    }

    /**
     * @param array{lap: ?int, time: ?float, fastest: ?bool, speed: ?float} $data
     */
    public static function fromData(array $data): self
    {
        return new self(
            $data['lap'],
            $data['time'],
            $data['fastest'],
            $data['speed'],
        );
    }
}
