<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence;

use Kishlin\Backend\Shared\Domain\Tools;
use ReflectionException;

final class LocationComputer
{
    /**
     * @param class-string<object>|object $object
     */
    public function computeLocation(string|object $object): string
    {
        try {
            return Tools::fromPascalToSnakeCase(Tools::shortClassName($object));
        } catch (ReflectionException) {
            return $object::class;
        }
    }
}
