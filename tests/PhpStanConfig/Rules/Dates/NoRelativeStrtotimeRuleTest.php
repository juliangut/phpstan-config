<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Dates;

use Jgut\PhpStanConfig\Rules\Dates\NoRelativeStrtotimeRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<NoRelativeStrtotimeRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Dates\NoRelativeStrtotimeRule
 */
final class NoRelativeStrtotimeRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'no-relative-strtotime' => [
            __DIR__ . '/Fixture/NoRelativeStrtotimeRule/Success/no-relative-strtotime.php',
        ];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        yield 'relative-strtotime' => [
            __DIR__ . '/Fixture/NoRelativeStrtotimeRule/Failure/relative-strtotime.php',
            [
                <<<'MESSAGE'
                Calling strtotime() with relative datetime "yesterday" without the second argument is forbidden.
                    💡 Rely on a clock abstraction like lcobucci/clock.
                MESSAGE,
                14,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        $reflectionProvider = self::getContainer()->getByType(ReflectionProvider::class);

        return new NoRelativeStrtotimeRule($reflectionProvider);
    }
}
