<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Rules\Classes\Fixture\ConstructorIsFirstMethodInClassRule\Failure;

// @codingStandardsIgnoreLine
final readonly class ConstructorNotFirst
{
    public function getValue(): string
    {
        return $this->value;
    }

    public function __construct(
        private string $value
    ) {}
}
