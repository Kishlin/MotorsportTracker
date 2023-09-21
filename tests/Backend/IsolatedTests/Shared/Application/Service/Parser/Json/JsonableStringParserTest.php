<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Application\Service\Parser\Json;

use Kishlin\Backend\Shared\Application\Service\Parser\Json\JsonableStringParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Application\Service\Parser\Json\JsonableStringParser
 */
final class JsonableStringParserTest extends TestCase
{
    public function testItCanParseData(): void
    {
        $data = '{"foo":"bar"}';

        $parser = new JsonableStringParser();

        self::assertSame(['foo' => 'bar'], $parser->parse($data));
    }
}
