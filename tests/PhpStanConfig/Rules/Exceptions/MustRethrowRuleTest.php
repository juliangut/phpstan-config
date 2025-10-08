<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Exceptions;

use Jgut\PhpStanConfig\Rules\Exceptions\MustRethrowRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<MustRethrowRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Exceptions\MustRethrowRule
 */
final class MustRethrowRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'rethrown-exception' => [__DIR__ . '/Fixture/MustRethrowRule/Success/rethrown-exception.php'];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        yield 'not-rethrown-exception' => [
            __DIR__ . '/Fixture/MustRethrowRule/Failure/not-rethrown-exception.php',
            [
                <<<'MESSAGE'
                caught "RuntimeException" must be rethrown.
                    💡 Either catch a more specific exception, add a "throw" clause in the "catch" block to propagate the exception or add a "// @ignoreException" comment.
                MESSAGE,
                18,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new MustRethrowRule();
    }
}
