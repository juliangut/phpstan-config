<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Exceptions;

use Jgut\PhpStanConfig\Rules\Exceptions\DoNotThrowExceptionBaseClassRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<DoNotThrowExceptionBaseClassRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Exceptions\DoNotThrowExceptionBaseClassRule
 */
final class DoNotThrowExceptionBaseClassRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'throw-derived-class' => [
            __DIR__ . '/Fixture/DoNotThrowExceptionBaseClassRule/Success/throw-derived-class.php',
        ];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        yield 'throw-base-class' => [
            __DIR__ . '/Fixture/DoNotThrowExceptionBaseClassRule/Failure/throw-base-class.php',
            [
                <<<'MESSAGE'
                Do not throw the \Exception base class.
                    💡 Throw a domain-specific exception that extends \Exception.
                MESSAGE,
                16,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new DoNotThrowExceptionBaseClassRule();
    }
}
