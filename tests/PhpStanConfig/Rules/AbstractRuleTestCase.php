<?php

/*
 * (c) 2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules;

use Jgut\PhpStanConfig\Rules\Statement\NoGotoRule;
use PHPStan\Testing\RuleTestCase;

/**
 * @internal
 *
 * @extends RuleTestCase<NoGotoRule>
 */
abstract class AbstractRuleTestCase extends RuleTestCase
{
    /**
     * @return iterable<string, list<string>>
     */
    abstract public static function analysisSucceedsProvider(): iterable;

    /**
     * @dataProvider analysisSucceedsProvider
     */
    final public function testAnalysisSucceeds(string $path): void
    {
        static::assertFileExists($path);

        $this->analyse([$path], []);
    }

    /**
     * @return iterable<string, array{0: string, 1: array{0: string, 1: int, 2?: string}}>
     */
    abstract public static function analysisFailsProvider(): iterable;

    /**
     * @dataProvider analysisFailsProvider
     *
     * @param array{0: string, 1: int, 2?: string} $expectedError
     */
    final public function testAnalysisFails(string $path, array $expectedError): void
    {
        static::assertFileExists($path);

        $this->analyse([$path], [$expectedError]);
    }
}
