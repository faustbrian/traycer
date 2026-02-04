<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Traycer\Strategies;

use Cline\Idempotency\HashAlgorithm;
use Cline\Idempotency\IdempotencyKey;
use Cline\Traycer\Contracts\TracingIdentifierStrategy;
use Illuminate\Http\Request;

use function is_string;

/**
 * Generates tracing identifiers from request JSON using idempotency hashing.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class IdempotencyStrategy implements TracingIdentifierStrategy
{
    /**
     * Create a new idempotency strategy instance.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(
        private array $config = [],
    ) {}

    /**
     * Generate a tracing identifier for the request.
     */
    public function generate(Request $request): string
    {
        $payload = $request->json()->all();
        $algorithm = $this->resolveAlgorithm();
        $prefix = $this->resolvePrefix();

        return (string) IdempotencyKey::create($payload, $algorithm, $prefix);
    }

    /**
     * Resolve the configured hash algorithm.
     */
    private function resolveAlgorithm(): ?HashAlgorithm
    {
        $algorithm = $this->config['algorithm'] ?? null;

        if (!is_string($algorithm) || $algorithm === '') {
            return null;
        }

        return HashAlgorithm::fromString($algorithm);
    }

    /**
     * Resolve the configured prefix.
     */
    private function resolvePrefix(): ?string
    {
        $prefix = $this->config['prefix'] ?? null;

        if (!is_string($prefix) || $prefix === '') {
            return null;
        }

        return $prefix;
    }
}
