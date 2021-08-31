<?php

namespace App\Models;

class RequestedLandingPosition
{
    private $area;
    private $xAxis;
    private $yAxis;
    private $usedPositions;

    public function __construct(ValidArea $area, int $xAxis, int $yAxis, array $usedPositions)
    {
        $this->area = $area;
        $this->xAxis = $xAxis;
        $this->yAxis = $yAxis;
        $this->usedPositions = $usedPositions;
    }

    /**
     * @return array
     */
    public function getUsedPositions()
    {
        return $this->usedPositions;
    }

    /**
     * @return int
     */
    public function getYAxis()
    {
        return $this->yAxis;
    }

    /**
     * @return int
     */
    public function getXAxis()
    {
        return $this->xAxis;
    }

    /**
     * @return ValidArea
     */
    public function getArea()
    {
        return $this->area;
    }
}
