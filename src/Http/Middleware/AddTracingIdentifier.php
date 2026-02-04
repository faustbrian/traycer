<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Traycer\Http\Middleware;

use Cline\Traycer\TraycerManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;

use function config;
use function is_string;

/**
 * Adds a tracing identifier to Laravel's log context for each request.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class AddTracingIdentifier
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(
        private TraycerManager $manager,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): mixed $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $identifier = $this->manager->generate($request);
        $contextKey = config('traycer.context_key', 'tracingIdentifier');

        if (!is_string($contextKey) || $contextKey === '') {
            $contextKey = 'tracingIdentifier';
        }

        Context::add($contextKey, $identifier);

        return $next($request);
    }
}
