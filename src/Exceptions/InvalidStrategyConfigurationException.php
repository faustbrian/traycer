<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use InvalidArgumentException;

use function sprintf;

/**
 * Exception thrown when correlation strategy configuration is invalid.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class InvalidStrategyConfigurationException extends InvalidArgumentException implements CorrelationException, ProvidesSolution
{
    /**
     * Strategy alias was not found in configuration.
     */
    public static function missingStrategy(string $alias): self
    {
        return new self(sprintf('Correlation strategy "%s" is not configured.', $alias));
    }

    /**
     * Strategy class is missing or not a string.
     */
    public static function missingClass(string $alias): self
    {
        return new self(sprintf('Correlation strategy "%s" does not define a valid class.', $alias));
    }

    /**
     * Strategy class does not implement the required contract.
     */
    public static function invalidClass(string $class): self
    {
        return new self(sprintf('Correlation strategy class "%s" must implement the correlation identifier strategy contract.', $class));
    }

    public function getSolution(): Solution
    {
        /** @var BaseSolution $solution */
        $solution = BaseSolution::create('Review package usage and configuration.');

        return $solution
            ->setSolutionDescription('Exception: '.$this->getMessage())
            ->setDocumentationLinks([
                'Package documentation' => 'https://github.com/cline/correlation',
            ]);
    }
}
