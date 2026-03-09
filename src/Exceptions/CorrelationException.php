<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation\Exceptions;

use Throwable;

/**
 * Marker interface for Correlation exceptions.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface CorrelationException extends Throwable {}
