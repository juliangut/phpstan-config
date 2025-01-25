<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Statement\Fixture\NoGotoRule\Success;

// @codingStandardsIgnoreLine
function _goto(string $names): void
{
    // NOOP
}

_goto('stage');

stage:
// NOOP
