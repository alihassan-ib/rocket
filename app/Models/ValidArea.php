<?php

namespace App\Models;

interface ValidArea
{
    public function getLandingArea();

    public function getStartXAxis();

    public function getStartYAxis();

    public function outsideValidArea(int $x, int $y);
}
