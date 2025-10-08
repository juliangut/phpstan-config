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
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use RuntimeException;
use Throwable;

/**
 * @implements Rule<Catch_>
 */
class MustRethrowRule implements Rule
{
    public function getNodeType(): string
    {
        return Catch_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $exceptionType = null;
        foreach ($node->types as $type) {
            if (\in_array((string) $type, [Throwable::class, RuntimeException::class, Throwable::class], true)) {
                $exceptionType = (string) $type;

                break;
            }
        }

        if ($exceptionType === null) {
            return [];
        }

        $visitor = new class () extends NodeVisitorAbstract {
            private bool $throwFound = false;

            public function leaveNode(Node $node): Node
            {
                if ($node instanceof Expression && $node->expr instanceof Throw_) {
                    $this->throwFound = true;
                }

                foreach ($node->getComments() as $comment) {
                    $this->throwFound = $this->throwFound || str_contains($comment->getText(), '@ignoreException');
                }

                return $node;
            }

            public function isThrowFound(): bool
            {
                return $this->throwFound;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($node->stmts);

        $errors = [];
        if (!$visitor->isThrowFound()) {
            $errors[] = RuleErrorBuilder::message(\sprintf(
                '%scaught "%s" must be rethrown.',
                $this->generatePrefix($scope),
                $exceptionType,
            ))
                ->tip(
                    'Either catch a more specific exception, add a "throw" clause in the "catch" block to propagate the exception or add a "// @ignoreException" comment.'
                )
                ->identifier('exception.not.rethrown')
                ->build();
        }

        return $errors;
    }

    private function generatePrefix(Scope $scope): string
    {
        $function = $scope->getFunction();
        $prefix = '';

        if ($function instanceof MethodReflection) {
            $prefix = \sprintf('In method "%s::%s", ', $function->getDeclaringClass()->getName(), $function->getName());
        } elseif ($function instanceof FunctionReflection) {
            $prefix = \sprintf('In function "%s", ', $function->getName());
        }

        return $prefix;
    }
}
