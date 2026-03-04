<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Idempotency\IdempotencyKey;
use Cline\Traycer\Strategies\IdempotencyStrategy;
use Illuminate\Http\Request;

it('generates a tracing identifier with a configured algorithm', function (): void {
    $strategy = new IdempotencyStrategy(['algorithm' => 'sha256']);

    $request = Request::create(
        uri: '/test',
        method: Symfony\Component\HttpFoundation\Request::METHOD_POST,
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(['key' => 'value']),
    );

    $result = $strategy->generate($request);

    expect($result)->toBeString()->not->toBeEmpty();
});

it('generates a tracing identifier with a configured prefix', function (): void {
    $strategy = new IdempotencyStrategy(['prefix' => 'trace']);

    $request = Request::create(
        uri: '/test',
        method: Symfony\Component\HttpFoundation\Request::METHOD_POST,
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(['key' => 'value']),
    );

    $result = $strategy->generate($request);
    $withoutPrefix = (string) IdempotencyKey::create(['key' => 'value']);

    // The prefix is included in the hash input, so the result differs from no-prefix
    expect($result)->toBeString()->not->toBe($withoutPrefix);
});

it('generates a tracing identifier with no configuration', function (): void {
    $strategy = new IdempotencyStrategy();

    $request = Request::create(
        uri: '/test',
        method: Symfony\Component\HttpFoundation\Request::METHOD_POST,
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(['key' => 'value']),
    );

    $result = $strategy->generate($request);
    $expected = (string) IdempotencyKey::create(['key' => 'value']);

    expect($result)->toBe($expected);
});
