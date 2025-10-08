<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

use Jgut\ECS\Config\ConfigSet82;
use JgutCodingStandard\Sniffs\CodeAnalysis\EmptyStatementSniff;
use JgutCodingStandard\Sniffs\NamingConventions\CamelCapsFunctionNameSniff;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$fixturesPath = __DIR__ . '/tests/PhpStanConfig/Rules';
$configSet = (new ConfigSet82())
    ->setHeader(<<<'HEADER'
    (c) 2023-{{year}} Julián Gutiérrez <juliangut@gmail.com>

    @license BSD-3-Clause
    @link https://github.com/juliangut/phpstan-config
    HEADER)
    ->setAdditionalSkips([
        LowercaseKeywordsFixer::class => [
            $fixturesPath . '/Statement/Fixture/NoGotoRule/Failure/goto-used-with-incorrect-case.php',
        ],
        CamelCapsFunctionNameSniff::class => [
            $fixturesPath . '/Statement/Fixture/NoGotoRule/Success/goto-not-used.php',
        ],
        EmptyStatementSniff::class => [
            $fixturesPath . '/Exceptions/Fixture/EmptyExceptionRule/Failure/not-captured-exception.php',
            $fixturesPath . '/Exceptions/Fixture/MustRethrowRule/Failure/not-rethrown-exception.php',
        ],
    ]);
$paths = [
    __FILE__,
    __DIR__ . '/src',
    __DIR__ . '/tests',
];

if (!method_exists(ECSConfig::class, 'configure')) {
    return static function (ECSConfig $ecsConfig) use ($configSet, $paths): void {
        $ecsConfig->paths($paths);

        $configSet->configure($ecsConfig);
    };
}

return $configSet
    ->configureBuilder()
    ->withPaths($paths);
