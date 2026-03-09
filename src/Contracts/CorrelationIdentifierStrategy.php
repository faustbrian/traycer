<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Correlation\Contracts;

use Illuminate\Http\Request;

/**
 * Strategy contract for generating correlation identifiers.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface CorrelationIdentifierStrategy
{
    /**
     * Generate a correlation identifier for the given request.
     */
    public function generate(Request $request): string;
}
