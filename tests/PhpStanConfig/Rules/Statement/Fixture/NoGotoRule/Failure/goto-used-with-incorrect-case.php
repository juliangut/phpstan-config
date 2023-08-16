<?php

/*
 * (c) 2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Statement\Fixture\NoGotoRule\Failure;

GoTo stage;

stage:
// NOOP
