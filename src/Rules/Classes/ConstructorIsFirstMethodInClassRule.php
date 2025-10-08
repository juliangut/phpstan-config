<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Rules\Classes;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Class_>
 */
class ConstructorIsFirstMethodInClassRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methods = $node->getMethods();
        if ($methods === []) {
            return [];
        }

        if ($node->getMethod('__construct') === null) {
            return [];
        }

        $first = reset($methods);
        if ($first->name->toLowerString() !== '__construct') {
            return [
                RuleErrorBuilder::message(\sprintf(
                    '__construct() should be the first method in the class (first method is "%s()").',
                    $first->name->toString(),
                ))
                    ->identifier('class.constructor.first')
                    ->build(),
            ];
        }

        return [];
    }
}
