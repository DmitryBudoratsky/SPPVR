<?php

namespace common\components\helpers;


class GpsHelper
{
    const equatorLengthMetres = 40075696;
    
    /** Метров в координате
     * @return number
     */
    public static function convertGpsCoordinatesToMeters($gpsDistance)
    {
        $gpsPerMeter = self::getGpsScalePerMeter();
        $meterPerGps = 1 / $gpsPerMeter;
        $distanceInMeters = $gpsDistance * $meterPerGps;
        return $distanceInMeters;  // 111316.6667      
    }
    
    /**
     * Координат в метре
     * @return number
     */
    public static function convertMetersToGpsCoordinates($distanceInMeters)
    {
        $gpsPerMeter = self::getGpsScalePerMeter();  // 0.0000089833807456 
        $gpsDistance = $distanceInMeters * $gpsPerMeter;
        return $gpsDistance;
    }
    
    /**
     * @return float
     */
    public static function getGpsScalePerMeter()
    {
    	return (360 / self::equatorLengthMetres);
    }
}