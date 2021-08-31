<?php

namespace App\Checkers;

use App\Exceptions\OutsideAreaException;
use App\Models\Enums\PositionsCodes;
use App\Models\RequestedLandingPosition;
use Closure;

class InsideLandingArea
{
    /**
     * @param RequestedLandingPosition $newPosition
     * @param Closure $next
     * @return mixed
     * @throws OutsideAreaException
     */
    public function handle(RequestedLandingPosition $newPosition, Closure $next)
    {
        $landingArea = $newPosition->getArea()->getLandingArea();
        if ($newPosition->getXAxis() > $landingArea->getXAxis() || $newPosition->getYAxis() > $landingArea->getYAxis())
            throw new OutsideAreaException(PositionsCodes::getCodeText(PositionsCodes::OUTSIDE_LANDING_AREA));

        return $next($newPosition);
    }
}
