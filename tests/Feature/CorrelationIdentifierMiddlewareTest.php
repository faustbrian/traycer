<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Correlation\CorrelationManager;
use Cline\Correlation\Http\Middleware\AddCorrelationIdentifier;
use Cline\Idempotency\IdempotencyKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Context;
use Tests\Fixtures\StaticStrategy;

it('adds an idempotency-based correlation identifier to the context', function (): void {
    Context::flush();

    Config::set('correlation.strategy', 'idempotency');
    Config::set('correlation.context_key', 'correlationIdentifier');

    $request = Request::create(
        uri: '/test',
        method: Symfony\Component\HttpFoundation\Request::METHOD_POST,
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(['order' => ['id' => 123, 'amount' => 49.95]]),
    );

    $middleware = new AddCorrelationIdentifier(resolve(CorrelationManager::class));

    $middleware->handle($request, fn (): null => null);

    $expected = (string) IdempotencyKey::create($request->json()->all());

    expect(Context::get('correlationIdentifier'))->toBe($expected);
});

it('accepts a custom strategy class for correlation identifiers', function (): void {
    Context::flush();

    Config::set('correlation.strategy', StaticStrategy::class);
    Config::set('correlation.context_key', 'correlationIdentifier');

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $middleware = new AddCorrelationIdentifier(resolve(CorrelationManager::class));

    $middleware->handle($request, fn (): null => null);

    expect(Context::get('correlationIdentifier'))->toBe('static-trace-id');
});

it('falls back to default context key when context_key config is empty string', function (): void {
    Context::flush();

    Config::set('correlation.strategy', StaticStrategy::class);
    Config::set('correlation.context_key', '');

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $middleware = new AddCorrelationIdentifier(resolve(CorrelationManager::class));

    $middleware->handle($request, fn (): null => null);

    expect(Context::get('correlationIdentifier'))->toBe('static-trace-id');
});
