<?php

/*
 * (c) 2023-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Rules\Statement;

use PhpParser\Node;
use PhpParser\Node\Stmt\Goto_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Goto_>
 */
final class NoGotoRule implements Rule
{
    public function getNodeType(): string
    {
        return Goto_::class;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function processNode(Node $node, Scope $scope): array
    {
        return [
            'Goto statement should not be used.',
        ];
    }
}
