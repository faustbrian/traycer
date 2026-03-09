<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Correlation\Exceptions\InvalidStrategyConfigurationException;

it('creates a missing strategy exception with the correct message', function (): void {
    $exception = InvalidStrategyConfigurationException::missingStrategy('my-strategy');

    expect($exception)->toBeInstanceOf(InvalidStrategyConfigurationException::class)
        ->and($exception->getMessage())->toBe('Correlation strategy "my-strategy" is not configured.');
});

it('creates a missing class exception with the correct message', function (): void {
    $exception = InvalidStrategyConfigurationException::missingClass('my-strategy');

    expect($exception)->toBeInstanceOf(InvalidStrategyConfigurationException::class)
        ->and($exception->getMessage())->toBe('Correlation strategy "my-strategy" does not define a valid class.');
});

it('creates an invalid class exception with the correct message', function (): void {
    $exception = InvalidStrategyConfigurationException::invalidClass('App\\MyStrategy');

    expect($exception)->toBeInstanceOf(InvalidStrategyConfigurationException::class)
        ->and($exception->getMessage())->toBe('Correlation strategy class "App\\MyStrategy" must implement the correlation identifier strategy contract.');
});

it('is an instance of InvalidArgumentException', function (): void {
    $exception = InvalidStrategyConfigurationException::missingStrategy('any');

    expect($exception)->toBeInstanceOf(InvalidArgumentException::class);
});
