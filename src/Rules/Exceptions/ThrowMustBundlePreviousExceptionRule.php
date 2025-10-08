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
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Catch_>
 */
class ThrowMustBundlePreviousExceptionRule implements Rule
{
    public function getNodeType(): string
    {
        return Catch_::class;
    }

    /**
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->var === null) {
            return [];
        }

        $caughtVarName = $node->var->name;
        if (!\is_string($caughtVarName)) {
            return [];
        }

        $visitor = new class ($caughtVarName) extends NodeVisitorAbstract {
            private int $exceptionUsedCount = 0;

            /**
             * @var list<Throw_>
             */
            private array $unusedThrows = [];

            public function __construct(
                private string $caughtVariableName
            ) {}

            public function leaveNode(Node $node): Node
            {
                if ($node instanceof Variable && $node->name === $this->caughtVariableName) {
                    ++$this->exceptionUsedCount;
                }

                if (
                    $node instanceof MethodCall
                    && $node->var instanceof Variable
                    && $node->var->name === $this->caughtVariableName
                ) {
                    --$this->exceptionUsedCount;
                }

                if ($node instanceof Throw_ && $this->exceptionUsedCount === 0) {
                    $this->unusedThrows[] = $node;
                }

                return $node;
            }

            /**
             * @return list<Throw_>
             */
            public function getUnusedThrows(): array
            {
                return $this->unusedThrows;
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($node->stmts);

        $errors = [];
        foreach ($visitor->getUnusedThrows() as $throw) {
            $errors[] = RuleErrorBuilder::message(\sprintf(
                'Thrown exceptions in a catch block must bundle the previous exception (see throw statement line %d).',
                $throw->getStartLine(),
            ))
                ->identifier('exception.must.rethrow')
                ->build();
        }

        return $errors;
    }
}
