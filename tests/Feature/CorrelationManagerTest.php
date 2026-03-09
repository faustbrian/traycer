<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Correlation\CorrelationManager;
use Cline\Correlation\Exceptions\InvalidStrategyConfigurationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tests\Fixtures\StaticStrategy;

it('throws missing strategy exception when strategy key is not found in strategies', function (): void {
    Config::set('correlation.strategy', 'nonexistent');
    Config::set('correlation.strategies', []);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $manager->generate($request);
})->throws(InvalidStrategyConfigurationException::class, 'Correlation strategy "nonexistent" is not configured.');

it('throws missing class exception when strategy config has no valid class', function (): void {
    Config::set('correlation.strategy', 'bad-strategy');
    Config::set('correlation.strategies', [
        'bad-strategy' => [
            'class' => null,
        ],
    ]);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $manager->generate($request);
})->throws(InvalidStrategyConfigurationException::class, 'Correlation strategy "bad-strategy" does not define a valid class.');

it('throws missing class exception when strategy config has empty class string', function (): void {
    Config::set('correlation.strategy', 'bad-strategy');
    Config::set('correlation.strategies', [
        'bad-strategy' => [
            'class' => '',
        ],
    ]);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $manager->generate($request);
})->throws(InvalidStrategyConfigurationException::class, 'Correlation strategy "bad-strategy" does not define a valid class.');

it('throws invalid class exception when strategy class does not implement contract', function (): void {
    Config::set('correlation.strategy', 'bad-strategy');
    Config::set('correlation.strategies', [
        'bad-strategy' => [
            'class' => stdClass::class,
        ],
    ]);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $manager->generate($request);
})->throws(InvalidStrategyConfigurationException::class, 'Correlation strategy class "stdClass" must implement the correlation identifier strategy contract.');

it('resolves strategy by fully qualified class name when class exists', function (): void {
    Config::set('correlation.strategy', StaticStrategy::class);
    Config::set('correlation.strategies', []);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $manager->generate($request);

    expect($result)->toBe('static-trace-id');
});

it('throws invalid class exception when fully qualified class does not implement contract', function (): void {
    Config::set('correlation.strategy', stdClass::class);
    Config::set('correlation.strategies', []);

    $manager = resolve(CorrelationManager::class);
    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $manager->generate($request);
})->throws(InvalidStrategyConfigurationException::class, 'Correlation strategy class "stdClass" must implement the correlation identifier strategy contract.');
