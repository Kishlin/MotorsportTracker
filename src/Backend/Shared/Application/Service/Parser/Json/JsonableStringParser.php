<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Application\Service\Parser\Json;

use InvalidArgumentException;
use JsonException;

final class JsonableStringParser
{
    /**
     * @return array<string, mixed>
     */
    public function parse(mixed $jsonableString): array
    {
        if (false === is_string($jsonableString)) {
            throw new InvalidArgumentException('The given argument is not a string');
        }

        try {
            $decoded = json_decode($jsonableString, true, flags: JSON_THROW_ON_ERROR);

            if (null === $decoded) {
                throw new ParserException('The given data might be deeper than recursion limit');
            }

            if (false === is_array($decoded)) {
                throw new ParserException('The given data is not a valid JSON');
            }

            return $decoded;
        } catch (JsonException $previous) {
            throw new ParserException('The given string is not a valid JSON', previous: $previous);
        }
    }
}
