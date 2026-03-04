<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Traycer\Exceptions;

use InvalidArgumentException;

use function sprintf;

/**
 * Exception thrown when traycer strategy configuration is invalid.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class InvalidStrategyConfigurationException extends InvalidArgumentException implements TraycerException
{
    /**
     * Strategy alias was not found in configuration.
     */
    public static function missingStrategy(string $alias): self
    {
        return new self(sprintf('Traycer strategy "%s" is not configured.', $alias));
    }

    /**
     * Strategy class is missing or not a string.
     */
    public static function missingClass(string $alias): self
    {
        return new self(sprintf('Traycer strategy "%s" does not define a valid class.', $alias));
    }

    /**
     * Strategy class does not implement the required contract.
     */
    public static function invalidClass(string $class): self
    {
        return new self(sprintf('Traycer strategy class "%s" must implement the tracing identifier strategy contract.', $class));
    }
}
