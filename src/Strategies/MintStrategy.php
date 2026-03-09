<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation\Strategies;

use Cline\Correlation\Contracts\CorrelationIdentifierStrategy;
use Cline\Mint\Enums\IdentifierType;
use Cline\Mint\MintManager;
use Illuminate\Http\Request;

use function is_array;
use function is_string;

/**
 * Generates correlation identifiers using the Mint identifier generators.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class MintStrategy implements CorrelationIdentifierStrategy
{
    /**
     * Create a new Mint strategy instance.
     *
     * @param array<string, mixed> $config
     */
    public function __construct(
        private MintManager $mint,
        private array $config = [],
    ) {}

    /**
     * Generate a correlation identifier for the request.
     */
    public function generate(Request $request): string
    {
        $type = $this->resolveType();
        $options = $this->resolveOptions();
        $generator = $this->mint->getGenerator($type, $options);

        return $generator->generate()->toString();
    }

    /**
     * Resolve the identifier type from configuration.
     */
    private function resolveType(): IdentifierType
    {
        $value = $this->config['type'] ?? IdentifierType::Ulid->value;

        if ($value instanceof IdentifierType) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $resolved = IdentifierType::tryFrom($value);

            if ($resolved instanceof IdentifierType) {
                return $resolved;
            }
        }

        return IdentifierType::Ulid;
    }

    /**
     * Resolve generator options for Mint.
     *
     * @return array<string, mixed>
     */
    private function resolveOptions(): array
    {
        $options = $this->config['options'] ?? [];

        if (!is_array($options)) {
            return [];
        }

        $resolved = [];

        foreach ($options as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            $resolved[$key] = $value;
        }

        return $resolved;
    }
}
