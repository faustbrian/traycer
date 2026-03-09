<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation;

use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Laravel service provider for Correlation package.
 *
 * @author Brian Faust <brian@cline.sh>
 */
final class CorrelationServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     *
     * @param Package $package The package instance to configure
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('correlation')
            ->hasConfigFile();
    }

    /**
     * Register Correlation services.
     */
    #[Override()]
    public function registeringPackage(): void
    {
        $this->app->singleton(CorrelationManager::class);
    }
}
