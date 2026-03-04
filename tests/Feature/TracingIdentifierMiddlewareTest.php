<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Idempotency\IdempotencyKey;
use Cline\Traycer\Http\Middleware\AddTracingIdentifier;
use Cline\Traycer\TraycerManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Context;
use Tests\Fixtures\StaticStrategy;

it('adds an idempotency-based tracing identifier to the context', function (): void {
    Context::flush();

    Config::set('traycer.strategy', 'idempotency');
    Config::set('traycer.context_key', 'tracingIdentifier');

    $request = Request::create(
        uri: '/test',
        method: Symfony\Component\HttpFoundation\Request::METHOD_POST,
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(['order' => ['id' => 123, 'amount' => 49.95]]),
    );

    $middleware = new AddTracingIdentifier(resolve(TraycerManager::class));

    $middleware->handle($request, fn (): null => null);

    $expected = (string) IdempotencyKey::create($request->json()->all());

    expect(Context::get('tracingIdentifier'))->toBe($expected);
});

it('accepts a custom strategy class for tracing identifiers', function (): void {
    Context::flush();

    Config::set('traycer.strategy', StaticStrategy::class);
    Config::set('traycer.context_key', 'tracingIdentifier');

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $middleware = new AddTracingIdentifier(resolve(TraycerManager::class));

    $middleware->handle($request, fn (): null => null);

    expect(Context::get('tracingIdentifier'))->toBe('static-trace-id');
});

it('falls back to default context key when context_key config is empty string', function (): void {
    Context::flush();

    Config::set('traycer.strategy', StaticStrategy::class);
    Config::set('traycer.context_key', '');

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $middleware = new AddTracingIdentifier(resolve(TraycerManager::class));

    $middleware->handle($request, fn (): null => null);

    expect(Context::get('tracingIdentifier'))->toBe('static-trace-id');
});
