<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Services\Shared;

use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;

trait RandomnessServicesTrait
{
    private ?UuidGenerator $uuidGenerator = null;

    public function uuidGenerator(): UuidGenerator
    {
        if (null === $this->uuidGenerator) {
            $this->uuidGenerator = new UuidGeneratorUsingRamsey();
        }

        return $this->uuidGenerator;
    }
}
