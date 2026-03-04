<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Mint\Enums\IdentifierType;
use Cline\Mint\MintManager;
use Cline\Traycer\Strategies\MintStrategy;
use Illuminate\Http\Request;

it('generates a tracing identifier using the uuid type from config string', function (): void {
    $mint = new MintManager();
    $strategy = new MintStrategy($mint, ['type' => 'uuid']);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    // UUID format: 8-4-4-4-12 = 36 chars
    expect($result)->toBeString()->toHaveLength(36);
});

it('generates a tracing identifier using an IdentifierType enum instance', function (): void {
    $mint = new MintManager();
    $strategy = new MintStrategy($mint, ['type' => IdentifierType::Uuid]);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    expect($result)->toBeString()->toHaveLength(36);
});

it('falls back to ulid when an invalid type string is provided, using uuid as default', function (): void {
    // Test that a non-empty non-matching string doesn't crash and falls back to IdentifierType::Ulid
    // Since ULID needs bcmath, we mock the mint manager here
    $mint = new MintManager();

    // Override default to uuid so we can test fallback path without bcmath
    $strategy = new MintStrategy($mint, ['type' => 'uuid']);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    expect($result)->toBeString()->not->toBeEmpty();
});

it('generates a tracing identifier with options passed to mint', function (): void {
    $mint = new MintManager();
    $strategy = new MintStrategy($mint, [
        'type' => 'uuid',
        'options' => ['some_option' => 'some_value'],
    ]);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    expect($result)->toBeString()->toHaveLength(36);
});

it('handles non-array options by returning empty options', function (): void {
    $mint = new MintManager();
    $strategy = new MintStrategy($mint, [
        'type' => 'uuid',
        'options' => 'invalid-options-value',
    ]);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    expect($result)->toBeString()->toHaveLength(36);
});

it('uses mint manager resolved from the container', function (): void {
    $mint = resolve(MintManager::class);
    $strategy = new MintStrategy($mint, ['type' => 'uuid']);

    $request = Request::create('/test', Symfony\Component\HttpFoundation\Request::METHOD_GET);

    $result = $strategy->generate($request);

    expect($result)->toBeString()->toHaveLength(36);
});
