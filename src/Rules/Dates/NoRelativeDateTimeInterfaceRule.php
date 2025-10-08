<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Rules\Dates;

use DateTimeInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * @implements Rule<New_>
 */
final class NoRelativeDateTimeInterfaceRule implements Rule
{
    private const RELATIVE_DATETIME_STRING = [
        'yesterday' => true,
        'midnight' => true,
        'today' => true,
        'now' => true,
        'noon' => true,
        'tomorrow' => true,
    ];

    public function getNodeType(): string
    {
        return New_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $type = $scope->getType($node);
        if (!(new ObjectType(DateTimeInterface::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        $args = $node->getArgs();

        if (\count($args) === 0) {
            return [
                RuleErrorBuilder::message(\sprintf(
                    'Instantiating %s without the first argument is forbidden.',
                    DateTimeInterface::class,
                ))
                    ->tip('Rely on a clock abstraction like lcobucci/clock.')
                    ->identifier('new.datetimeinterface.implicitTime.forbidden')
                    ->build(),
            ];
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
                        'Instantiating %s with relative datetime "%s" is forbidden.',
                        DateTimeInterface::class,
                        $value,
                    ))
                        ->tip('Rely on a clock abstraction like lcobucci/clock.')
                        ->identifier('new.datetimeinterface.relativeTime.forbidden')
                        ->build(),
                ];
            }
        }

        return [];
    }
}
