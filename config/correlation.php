<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\Correlation\Strategies\IdempotencyStrategy;
use Cline\Correlation\Strategies\MintStrategy;

return [
    /*
    |--------------------------------------------------------------------------
    | Context Key
    |--------------------------------------------------------------------------
    |
    | The key used when storing the correlation identifier in Laravel's
    | log context repository.
    |
    */

    'context_key' => env('CORRELATION_CONTEXT_KEY', 'correlationIdentifier'),

    /*
    |--------------------------------------------------------------------------
    | Strategy Selection
    |--------------------------------------------------------------------------
    |
    | Determines which strategy generates correlation identifiers. You may
    | set this to a strategy alias from the map below or a fully-qualified
    | class name that implements the strategy contract.
    |
    */

    'strategy' => env('CORRELATION_STRATEGY', 'idempotency'),

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
            'prefix' => env('CORRELATION_IDEMPOTENCY_PREFIX'),
            'algorithm' => env('CORRELATION_IDEMPOTENCY_ALGORITHM'),
        ],
        'mint' => [
            'class' => MintStrategy::class,
            'type' => env('CORRELATION_MINT_TYPE', 'ulid'),
            'options' => [],
        ],
    ],
];
