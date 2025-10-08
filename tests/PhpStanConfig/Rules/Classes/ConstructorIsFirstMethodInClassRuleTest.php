<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Classes;

use Jgut\PhpStanConfig\Rules\Classes\ConstructorIsFirstMethodInClassRule;
use Jgut\PhpStanConfig\Tests\Rules\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends AbstractRuleTestCase<ConstructorIsFirstMethodInClassRule>
 *
 * @covers \Jgut\PhpStanConfig\Rules\Classes\ConstructorIsFirstMethodInClassRule
 */
final class ConstructorIsFirstMethodInClassRuleTest extends AbstractRuleTestCase
{
    public static function provideAnalysisSucceedsCases(): iterable
    {
        yield 'constructor-only' => [
            __DIR__ . '/Fixture/ConstructorIsFirstMethodInClassRule/Success/ConstructorOnly.php',
        ];
        yield 'constructor-first' => [
            __DIR__ . '/Fixture/ConstructorIsFirstMethodInClassRule/Success/ConstructorFirst.php',
        ];
    }

    public static function provideAnalysisFailsCases(): iterable
    {
        yield 'constructor-not-first' => [
            __DIR__ . '/Fixture/ConstructorIsFirstMethodInClassRule/Failure/ConstructorNotFirst.php',
            [
                '__construct() should be the first method in the class (first method is "getValue()").',
                15,
            ],
        ];
    }

    protected function getRule(): Rule
    {
        return new ConstructorIsFirstMethodInClassRule();
    }
}
