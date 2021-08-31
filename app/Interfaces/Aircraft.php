<?php

namespace App\Interfaces;

abstract class Aircraft
{
    private $uuid;

    public function __construct()
    {
        $this->uuid = rand(1000, 9999);
    }

    /**
     * @return int
     */
    public function getUuid(): int
    {
        return $this->uuid;
    }
}
