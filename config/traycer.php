<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Traycer\Strategies\IdempotencyStrategy;
use Cline\Traycer\Strategies\MintStrategy;

return [
    /*
    |--------------------------------------------------------------------------
    | Context Key
    |--------------------------------------------------------------------------
    |
    | The key used when storing the tracing identifier in Laravel's
    | log context repository.
    |
    */

    'context_key' => env('TRAYCER_CONTEXT_KEY', 'tracingIdentifier'),

    /*
    |--------------------------------------------------------------------------
    | Strategy Selection
    |--------------------------------------------------------------------------
    |
    | Determines which strategy generates tracing identifiers. You may
    | set this to a strategy alias from the map below or a fully-qualified
    | class name that implements the strategy contract.
    |
    */

    'strategy' => env('TRAYCER_STRATEGY', 'idempotency'),

    /*
    |--------------------------------------------------------------------------
    | Strategy Configuration
    |--------------------------------------------------------------------------
    |
    | Configure each strategy via its alias. The configured "class" will
    | be resolved from the container and passed the configuration array.
    |
    */

    'strategies' => [
        'idempotency' => [
            'class' => IdempotencyStrategy::class,
            'prefix' => env('TRAYCER_IDEMPOTENCY_PREFIX'),
            'algorithm' => env('TRAYCER_IDEMPOTENCY_ALGORITHM'),
        ],
        'mint' => [
            'class' => MintStrategy::class,
            'type' => env('TRAYCER_MINT_TYPE', 'ulid'),
            'options' => [],
        ],
    ],
];
