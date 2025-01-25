<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

use Jgut\ECS\Config\ConfigSet80;
use JgutCodingStandard\Sniffs\NamingConventions\CamelCapsFunctionNameSniff;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

$fixturesPath = __DIR__ . '/tests/PhpStanConfig/Rules/Statement/Fixture';
$configSet = (new ConfigSet80())
    ->setHeader(<<<'HEADER'
    (c) 2023-{{year}} Julián Gutiérrez <juliangut@gmail.com>

    @license BSD-3-Clause
    @link https://github.com/juliangut/phpstan-config
    HEADER)
    ->setAdditionalSkips([
        LowercaseKeywordsFixer::class => [
            $fixturesPath . '/NoGotoRule/Failure/goto-used-with-incorrect-case.php',
        ],
        CamelCapsFunctionNameSniff::class => [
            $fixturesPath . '/NoGotoRule/Success/goto-not-used.php',
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
