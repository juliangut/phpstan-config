<?php

/*
 * (c) 2023-2025 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/phpstan-config
 */

declare(strict_types=1);

namespace Jgut\PhpStanConfig\Tests\Type\Fixture\ContainerDynamicReturnTypeExtension;

use Psr\Container\ContainerInterface;
use stdClass;

use function PHPStan\Testing\assertType;

static function (ContainerInterface $container): void {
    // Unknown
    assertType(
        'mixed',
        $container->get('unknown')
    );
    assertType(
        'mixed',
        $container->get('Jgut\PhpStanConfig\Tests\Type\Fixture\ContainerDynamicReturnTypeExtension\Unknown')
    );

    // Traits
    assertType(
        'mixed',
        $container->get(ExampleServiceTrait::class)
    );
    assertType(
        'mixed',
        $container->get('Jgut\PhpStanConfig\Tests\Type\Fixture\ContainerDynamicReturnTypeExtension\ExampleServiceTrait')
    );

    // Classes
    assertType(
        'stdClass',
        $container->get('stdClass')
    );
    assertType(
        'stdClass',
        $container->get(stdClass::class)
    );
    assertType(
        ExampleServiceClass::class,
        $container->get(ExampleServiceClass::class)
    );
    assertType(
        ExampleServiceClass::class,
        $container->get('Jgut\PhpStanConfig\Tests\Type\Fixture\ContainerDynamicReturnTypeExtension\ExampleServiceClass')
    );

    // Interfaces
    assertType(
        ExampleServiceInterface::class,
        $container->get(ExampleServiceInterface::class)
    );
    assertType(
        ExampleServiceInterface::class,
        $container->get(
            'Jgut\PhpStanConfig\Tests\Type\Fixture\ContainerDynamicReturnTypeExtension\ExampleServiceInterface'
        )
    );
};
