<?php

namespace App\Interfaces;

use App\Exceptions\InvalidatePlatformCoordinatesException;
use Illuminate\Support\Arr;

abstract class Area
{
    private $xAxis;
    private $yAxis;

    /**
     * @throws InvalidatePlatformCoordinatesException
     */
    public function __construct(int $xAxis, int $yAxis)
    {
        if ($this->negativeCoordinates($xAxis, $yAxis))
            throw new InvalidatePlatformCoordinatesException('Invalid coordinates !');

        $this->xAxis = $xAxis;
        $this->yAxis = $yAxis;
    }

    /**
     * @return int
     */
    public function getXAxis(): int
    {
        return $this->xAxis;
    }

    /**
     * @return int
     */
    public function getYAxis(): int
    {
        return $this->yAxis;
    }

    /**
     * @param mixed ...$args
     * @return bool
     * @author Ali Hassan
     */
    protected function negativeCoordinates(...$args): bool
    {
        foreach (Arr::wrap($args) as $arg)
            if ($arg < 0)
                return true;

        return false;
    }
}
