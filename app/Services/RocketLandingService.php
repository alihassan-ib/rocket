<?php

namespace App\Services;

use App\Checkers\FarAwayFromUsedPosition;
use App\Checkers\InsideEmptyCoordinates;
use App\Checkers\InsideLandingArea;
use App\Checkers\InsidePlatformArea;
use App\Exceptions\CoordinatesException;
use App\Interfaces\Aircraft;
use App\Models\Enums\PositionsCodes;
use App\Models\RequestedLandingPosition;
use App\Models\ValidArea;
use Illuminate\Pipeline\Pipeline;

class RocketLandingService
{
    private $area;
    private static $positions = [];
    private static $invalidRockets = [];

    /**
     * @param ValidArea $area
     */
    public function __construct(ValidArea $area)
    {
        $this->area = $area;
    }

    /**
     * @return array
     */
    public function getInvalidRockets(): array
    {
        return self::$invalidRockets;
    }

    /**
     * @param Aircraft $aircraft
     * @param int $xAxis
     * @param int $yAxis
     * @return string
     */
    public function land(Aircraft $aircraft, int $xAxis, int $yAxis)
    {
        try{
            app(Pipeline::class)
                ->send(new RequestedLandingPosition($this->area, $xAxis, $yAxis, self::$positions))
                ->through([
                    InsideLandingArea::class,
                    InsidePlatformArea::class,
                    InsideEmptyCoordinates::class,
                    FarAwayFromUsedPosition::class
                ])->then(function(RequestedLandingPosition $newPosition) use ($aircraft) {
                    self::$positions[$newPosition->getXAxis()][$newPosition->getYAxis()] = $aircraft;
                    if(isset(self::$invalidRockets[$aircraft->getUuid()]))
                        unset(self::$invalidRockets[$aircraft->getUuid()]);
                });
        }catch (CoordinatesException $exception){
            self::$invalidRockets[$aircraft->getUuid()] = $aircraft;
            return $exception->getMessage();
        }

        return PositionsCodes::getCodeText(PositionsCodes::OK);
    }
}
