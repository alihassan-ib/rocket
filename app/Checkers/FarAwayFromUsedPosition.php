<?php

namespace App\Checkers;

use App\Exceptions\NextToUsedPositionException;
use App\Models\Enums\PositionsCodes;
use App\Models\RequestedLandingPosition;
use App\Models\ValidArea;
use Closure;

class FarAwayFromUsedPosition
{
    private $nextInvalidPositionCounter = 1;

    /**
     * @param RequestedLandingPosition $newPosition
     * @param Closure $next
     * @return mixed
     * @throws NextToUsedPositionException
     */
    public function handle(RequestedLandingPosition $newPosition, Closure $next)
    {
        if($newPosition->getUsedPositions() && $this->isNextToUsedPosition($newPosition->getArea(), $newPosition->getXAxis(), $newPosition->getYAxis(), $newPosition->getUsedPositions()))
            throw new NextToUsedPositionException(PositionsCodes::getCodeText(PositionsCodes::NEXT_TO_USED_POSITION));

        return $next($newPosition);
    }

    private function isNextToUsedPosition(ValidArea $platformArea, $xAxis, $yAxis, $positions): bool
    {
        for ($x = -$this->nextInvalidPositionCounter; $x <= $this->nextInvalidPositionCounter; $x++)
            for ($y = -$this->nextInvalidPositionCounter; $y <= $this->nextInvalidPositionCounter; $y++){
                if(($x != 0 || $y != 0) && !$platformArea->outsideValidArea($xAxis + $x, $yAxis + $y) && isset($positions[$xAxis + $x][$yAxis + $y])){
                    return true;
                }
            }
        return false;
    }
}
