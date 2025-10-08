<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Rules\Exceptions;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Throw_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * From thecodingmachine/phpstan-strict-rules.
 *
 * @implements Rule<Throw_>
 */
class DoNotThrowExceptionBaseClassRule implements Rule
{
    public function getNodeType(): string
    {
        return Throw_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->expr instanceof New_) {
            return [];
        }

        $classNames = array_map(
            static fn(string $class) => mb_ltrim($class, '\\'),
            $scope->getType($node->expr)
                ->getObjectClassNames(),
        );
        if (\count($classNames) === 1 && $classNames[0] === 'Exception') {
            return [
                RuleErrorBuilder::message('Do not throw the \Exception base class.')
                    ->tip('Throw a domain-specific exception that extends \Exception.')
                    ->identifier('exception.baseclass.thrown')
                    ->build(),
            ];
        }

        return [];
    }
}
