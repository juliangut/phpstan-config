<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Dates;

use Jgut\PhpStanConfig\Rules\Dates\NoRelativeDateTimeInterfaceRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<NoRelativeDateTimeInterfaceRule>
 */
#[CoversClass(NoRelativeDateTimeInterfaceRule::class)]
final class NoRelativeDateTimeInterfaceRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'no-relative-datetime' => [
            __DIR__ . '/Fixture/NoRelativeDateTimeInterfaceRule/Success/no-relative-datetime.php',
        ];
        yield 'no-relative-datetimeimmutable' => [
            __DIR__ . '/Fixture/NoRelativeDateTimeInterfaceRule/Success/no-relative-datetimeimmutable.php',
        ];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        $fixturesPath = __DIR__ . '/Fixture/NoRelativeDateTimeInterfaceRule';

        yield 'relative-datetime' => [
            $fixturesPath . '/Failure/relative-datetime.php',
            [
                <<<'MESSAGE'
                Instantiating DateTimeInterface with relative datetime "yesterday" is forbidden.
                    💡 Rely on a clock abstraction like lcobucci/clock.
                MESSAGE,
                16,
            ],
        ];
        yield 'relative-datetimeimmutable' => [
            $fixturesPath . '/Failure/relative-datetimeimmutable.php',
            [
                <<<'MESSAGE'
                Instantiating DateTimeInterface with relative datetime "yesterday" is forbidden.
                    💡 Rely on a clock abstraction like lcobucci/clock.
                MESSAGE,
                16,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new NoRelativeDateTimeInterfaceRule();
    }
}
