<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Rules\Dates;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<FuncCall>
 */
final class NoRelativeStrtotimeRule implements Rule
{
    private const RELATIVE_DATETIME_STRING = [
        'yesterday' => true,
        'midnight' => true,
        'today' => true,
        'now' => true,
        'noon' => true,
        'tomorrow' => true,
    ];

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {}

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Name) {
            return [];
        }

        if ($this->reflectionProvider->resolveFunctionName($node->name, $scope) !== 'strtotime') {
            return [];
        }

        $args = $node->getArgs();
        if (\count($args) > 1) {
            return [];
        }

        $argType = $scope->getType($args[0]->value);
        $constStrings = $argType->getConstantStrings();
        if ($constStrings === []) {
            return [];
        }

        foreach ($constStrings as $string) {
            $value = $string->getValue();

            if (\array_key_exists($value, self::RELATIVE_DATETIME_STRING)) {
                return [
                    RuleErrorBuilder::message(\sprintf(
                        'Calling strtotime() with relative datetime "%s" without the second argument is forbidden.',
                        $value,
                    ))
                        ->tip('Rely on a clock abstraction like lcobucci/clock.')
                        ->identifier('function.strtotime.relativeTime.forbidden')
                        ->build(),
                ];
            }
        }

        return [];
    }
}
