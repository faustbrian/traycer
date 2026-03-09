<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Fixtures;

use Cline\Correlation\Contracts\CorrelationIdentifierStrategy;
use Illuminate\Http\Request;

/**
 * @author Brian Faust <brian@cline.sh>
 * @psalm-immutable
 */
final readonly class StaticStrategy implements CorrelationIdentifierStrategy
{
    public function generate(Request $request): string
    {
        return 'static-trace-id';
    }
}
