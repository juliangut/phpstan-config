<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Type\Container;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Psr\Container\ContainerInterface;

/**
 * From kcs/psr-phpstan-rules.
 */
class ContainerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return ContainerInterface::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'get';
    }

    /**
     * @SuppressWarnings(CyclomaticComplexity)
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        /** @var list<Arg> $args */
        $args = array_values(array_filter($methodCall->args, static fn($arg): bool => $arg instanceof Arg));

        $fallback = static fn(): Type => ParametersAcceptorSelector::selectFromArgs(
            $scope,
            $args,
            $methodReflection->getVariants()
        )->getReturnType();

        if ($args === []) {
            return $fallback();
        }

        $first = $args[0]->value;

        // Try to resolve a concrete class/interface name from the first argument.
        $candidate = null;

        if ($first instanceof ClassConstFetch) {
            $type = $scope->getType($first);

            $constStrings = $type->getConstantStrings();
            if ($constStrings !== []) {
                $candidate = $constStrings[0]->getValue();
            } else {
                $objType = $type->getClassStringObjectType();
                $names = $objType->getObjectClassNames();
                if ($names !== []) {
                    $candidate = $names[0];
                }
            }
        } elseif ($first instanceof String_) {
            $constStrings = $scope->getType($first)
                ->getConstantStrings();
            $candidate = $constStrings !== [] ? $constStrings[0]->getValue() : $first->value;
        } else {
            return $fallback();
        }

        if ($candidate === null || $candidate === '') {
            return $fallback();
        }

        // Only return a concrete object type for real classes/interfaces (exclude traits).
        if ((class_exists($candidate) || interface_exists($candidate))
            && !trait_exists($candidate)
        ) {
            return new ObjectType(mb_ltrim($candidate, '\\'));
        }

        return $fallback(); // -> mixed
    }
}
