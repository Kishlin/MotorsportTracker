<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Tools\Helpers;

use Kishlin\Backend\Tools\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class StringHelperTest extends TestCase
{
    public function testItCanSlugify(): void
    {
        self::assertSame('formula-one', StringHelper::slugify('Formula One'));
        self::assertSame('formula-one_dutch-grand-prix', StringHelper::slugify('Formula One', 'Dutch Grand Prix'));
        self::assertSame('formula-one_dutch-grand-prix_race', StringHelper::slugify('Formula One', 'Dutch Grand Prix', 'Race'));
    }

    public function testItKeepsUnderscores(): void
    {
        self::assertSame('test_underscore', StringHelper::slugify('test_underscore'));
    }
}
