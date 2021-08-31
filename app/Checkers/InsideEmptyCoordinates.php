<?php

namespace App\Checkers;

use App\Exceptions\UsedPlatformPositionException;
use App\Models\Enums\PositionsCodes;
use App\Models\RequestedLandingPosition;
use Closure;

class InsideEmptyCoordinates
{
    /**
     * @param RequestedLandingPosition $newPosition
     * @param Closure $next
     * @return mixed
     * @throws UsedPlatformPositionException
     */
    public function handle(RequestedLandingPosition $newPosition, Closure $next)
    {
        if(isset($newPosition->getUsedPositions()[$newPosition->getXAxis()][$newPosition->getYAxis()]))
            throw new UsedPlatformPositionException(PositionsCodes::getCodeText(PositionsCodes::USED_POSITION));

        return $next($newPosition);
    }
}
