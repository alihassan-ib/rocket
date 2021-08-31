<?php

namespace App\Checkers;

use App\Exceptions\OutsidePlatformAreaException;
use App\Models\Enums\PositionsCodes;
use App\Models\RequestedLandingPosition;
use Closure;

class InsidePlatformArea
{
    /**
     * @param RequestedLandingPosition $newPosition
     * @param Closure $next
     * @return mixed
     * @throws OutsidePlatformAreaException
     */
    public function handle(RequestedLandingPosition $newPosition, Closure $next)
    {
        if ($newPosition->getArea()->outsideValidArea($newPosition->getXAxis(), $newPosition->getYAxis()))
            throw new OutsidePlatformAreaException(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_PLATFORM_AREA));

        return $next($newPosition);
    }
}
