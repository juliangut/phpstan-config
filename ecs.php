<?php

/*
 * (c) 2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

use Jgut\ECS\Config\ConfigSet80;
use Jgut\ECS\Config\ConfigSet81;
use Jgut\ECS\Config\ConfigSet82;
use JgutCodingStandard\Sniffs\NamingConventions\CamelCapsFunctionNameSniff;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $header = <<<'HEADER'
    (c) 2023-{{year}} Julián Gutiérrez <juliangut@gmail.com>

    @license BSD-3-Clause
    @link https://github.com/juliangut/phpstan-config
    HEADER;

    $ecsConfig->paths([
        __FILE__,
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    if (\PHP_VERSION_ID >= 80_200) {
        $configSet = new ConfigSet82();
    } elseif (\PHP_VERSION_ID >= 80_100) {
        $configSet = new ConfigSet81();
    } else {
        $configSet = new ConfigSet80();
    }

    $fixturesPath = __DIR__ . '/tests/PhpStanConfig/Rules/Statement/Fixture';

    $configSet
        ->setHeader($header)
        ->enablePhpUnitRules()
        ->setAdditionalSkips([
            LowercaseKeywordsFixer::class => [
                $fixturesPath . '/NoGotoRule/Failure/goto-used-with-incorrect-case.php',
            ],
            CamelCapsFunctionNameSniff::class => [
                $fixturesPath . '/NoGotoRule/Success/goto-not-used.php',
            ],
        ])
        ->configure($ecsConfig);
};
