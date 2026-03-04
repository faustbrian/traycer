<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Traycer;

use Cline\Traycer\Contracts\TracingIdentifierStrategy;
use Cline\Traycer\Exceptions\InvalidStrategyConfigurationException;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

use function class_exists;
use function is_array;
use function is_string;

/**
 * Resolves and executes tracing identifier strategies.
 *
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class TraycerManager
{
    /**
     * Create a new Traycer manager instance.
     */
    public function __construct(
        private ConfigRepository $config,
        private Container $container,
    ) {}

    /**
     * Generate a tracing identifier for the given request.
     */
    public function generate(Request $request): string
    {
        $strategy = $this->resolveStrategy();

        return $strategy->generate($request);
    }

    /**
     * Resolve the configured tracing identifier strategy.
     *
     * @throws InvalidStrategyConfigurationException
     */
    private function resolveStrategy(): TracingIdentifierStrategy
    {
        $strategyKey = $this->config->get('traycer.strategy', 'idempotency');
        $strategies = $this->config->get('traycer.strategies', []);

        if (is_string($strategyKey) && is_array($strategies) && isset($strategies[$strategyKey])) {
            $strategyConfig = $strategies[$strategyKey];
            $class = is_array($strategyConfig) ? ($strategyConfig['class'] ?? null) : $strategyConfig;

            if (!is_string($class) || $class === '') {
                throw InvalidStrategyConfigurationException::missingClass($strategyKey);
            }

            $instance = $this->container->make($class, [
                'config' => is_array($strategyConfig) ? $strategyConfig : [],
            ]);

            if (!$instance instanceof TracingIdentifierStrategy) {
                throw InvalidStrategyConfigurationException::invalidClass($class);
            }

            return $instance;
        }

        if (is_string($strategyKey) && class_exists($strategyKey)) {
            $instance = $this->container->make($strategyKey);

            if (!$instance instanceof TracingIdentifierStrategy) {
                throw InvalidStrategyConfigurationException::invalidClass($strategyKey);
            }

            return $instance;
        }

        $missingKey = is_string($strategyKey) ? $strategyKey : 'unknown';

        throw InvalidStrategyConfigurationException::missingStrategy($missingKey);
    }
}
