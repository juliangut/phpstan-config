<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Exceptions;

use Jgut\PhpStanConfig\Rules\Exceptions\ThrowMustBundlePreviousExceptionRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<ThrowMustBundlePreviousExceptionRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Exceptions\ThrowMustBundlePreviousExceptionRule
 */
final class ThrowMustBundlePreviousExceptionRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'rethrown-previous-exception' => [
            __DIR__ . '/Fixture/ThrowMustBundlePreviousExceptionRule/Success/rethrown-previous-exception.php',
        ];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        yield 'rethrown-not-previous-exception' => [
            __DIR__ . '/Fixture/ThrowMustBundlePreviousExceptionRule/Failure/rethrown-not-previous-exception.php',
            [
                'Thrown exceptions in a catch block must bundle the previous exception (see throw statement line 20).',
                18,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new ThrowMustBundlePreviousExceptionRule();
    }
}
