<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation\Http\Middleware;

use Cline\Correlation\CorrelationManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;

use function config;
use function is_string;

/**
 * Adds a correlation identifier to Laravel's log context for each request.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class AddCorrelationIdentifier
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(
        private CorrelationManager $manager,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): mixed $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $identifier = $this->manager->generate($request);
        $contextKey = config('correlation.context_key', 'correlationIdentifier');

        if (!is_string($contextKey) || $contextKey === '') {
            $contextKey = 'correlationIdentifier';
        }

        Context::add($contextKey, $identifier);

        return $next($request);
    }
}
