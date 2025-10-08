<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Type;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ContainerDynamicReturnTypeExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<array<array-key, mixed>>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/Fixture/ContainerDynamicReturnTypeExtension/container.php');
    }

    /**
     * @param string|int ...$args
     */
    #[DataProvider('dataFileAsserts')]
    public function testFileAsserts(string $assertType, string $file, ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/Fixture/rules.neon'];
    }
}
