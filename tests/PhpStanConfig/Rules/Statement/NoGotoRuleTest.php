<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Statement;

use Jgut\PhpStanConfig\Rules\Statement\NoGotoRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<NoGotoRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Statement\NoGotoRule
 */
final class NoGotoRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'goto-not-used' => [__DIR__ . '/Fixture/NoGotoRule/Success/goto-not-used.php'];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        $fixturesPath = __DIR__ . '/Fixture/NoGotoRule';

        yield 'goto-used-with-correct-case' => [
            $fixturesPath . '/Failure/goto-used-with-correct-case.php',
            [
                'Goto statement should not be used.',
                14,
            ],
        ];

        yield 'goto-used-with-incorrect-case' => [
            $fixturesPath . '/Failure/goto-used-with-incorrect-case.php',
            [
                'Goto statement should not be used.',
                14,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new NoGotoRule();
    }
}
