<?php

namespace App\Models\Enums;

class PositionsCodes
{
    const OK = 1;
    const OUTSIDE_LANDING_AREA = 2;
    const OUTSIDE_PLATFORM_AREA = 3;
    const USED_POSITION = 4;
    const NEXT_TO_USED_POSITION = 5;

    public static function getCodeText(int $code)
    {
        $message = '';

        switch ($code){
            case self::OK:
                $message = 'Ok for landing';
                break;
            case self::OUTSIDE_LANDING_AREA:
                $message = 'Coordinates outside landing area !';
                break;
            case self::OUTSIDE_PLATFORM_AREA:
                $message = 'Out of platform !';
                break;
            case self::USED_POSITION || self::NEXT_TO_USED_POSITION:
                $message = 'Clash !';
                break;
        }

        return $message;
    }
}
