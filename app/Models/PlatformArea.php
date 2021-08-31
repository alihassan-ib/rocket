<?php

namespace App\Models;

use App\Exceptions\InvalidatePlatformCoordinatesException;
use App\Interfaces\Area;

class PlatformArea extends Area implements ValidArea
{
    private $landingArea;
    private $xStart;
    private $yStart;

    /**
     * @throws InvalidatePlatformCoordinatesException
     */
    public function __construct(int $xStart, int $yStart, int $xAxis, int $yAxis, Area $landingArea)
    {
        $x = $xAxis + $xStart - 1;
        $y = $yAxis + $yStart - 1;

        if ($this->negativeCoordinates($xStart, $yStart, $xAxis, $yAxis) ||
            $this->isOutsideLandingArea($landingArea, $x , $y))
            throw new InvalidatePlatformCoordinatesException('Invalid platform coordinates !');

        $this->landingArea = $landingArea;
        $this->xStart = $xStart;
        $this->yStart = $yStart;

        parent::__construct($x, $y);
    }

    /**
     * @param Area $landingArea
     * @param int $xAxis
     * @param int $yAxis
     * @return bool
     */
    private function isOutsideLandingArea(Area $landingArea, int $xAxis, int $yAxis): bool
    {
        return $landingArea->getXAxis() < $xAxis || $landingArea->getYAxis() < $yAxis;
    }

    /**
     * @return Area
     */
    public function getLandingArea(): Area
    {
        return $this->landingArea;
    }

    public function getStartXAxis()
    {
        return $this->xStart;
    }

    public function getStartYAxis()
    {
        return $this->yStart;
    }

    /**
     * @param $x
     * @param $y
     * @return bool
     */
    public function outsideValidArea($x, $y): bool
    {
        return $x > $this->getXAxis() || $y > $this->getYAxis()
        || $x < $this->getStartXAxis() || $y < $this->getStartYAxis();
    }
}
