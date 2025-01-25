<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @internal
 *
 * @template TRule of Rule
 *
 * @extends RuleTestCase<TRule>
 */
abstract class AbstractRuleTestCase extends RuleTestCase
{
    /**
     * @return iterable<string, list<string>>
     */
    abstract public static function provideAnalysisSucceedsCases(): iterable;

    /**
     * @dataProvider provideAnalysisSucceedsCases
     */
    final public function testAnalysisSucceeds(string $path): void
    {
        static::assertFileExists($path);

        $this->analyse([$path], []);
    }

    /**
     * @return iterable<string, array{0: string, 1: array{0: string, 1: int, 2?: string}}>
     */
    abstract public static function provideAnalysisFailsCases(): iterable;

    /**
     * @dataProvider provideAnalysisFailsCases
     *
     * @param array{0: string, 1: int, 2?: string} $expectedError
     */
    final public function testAnalysisFails(string $path, array $expectedError): void
    {
        static::assertFileExists($path);

        $this->analyse([$path], [$expectedError]);
    }
}
