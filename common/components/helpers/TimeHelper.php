<?php

namespace common\components\helpers;


use DateTime;

class TimeHelper
{  
	/** Секунды из дней
	 * @param integer $daysCount
	 * @return integer
	 */
	public static function getSecondsFromDays($daysCount)
	{
		return $daysCount * 24 * 60 * 60;
	}
	
	/** Секунды из часов
	 * @param integer $hoursCount
	 * @return integer
	 */
	public static function getSecondsFromHours($hoursCount)
	{
		return $hoursCount * 60 * 60;
	}
	
	/** Секунды из минут
	 * @param integer $minutesCount
	 * @return integer
	 */
	public static function getSecondsFromMinutes($minutesCount)
	{
		return $minutesCount * 60;
	}
	
	/** Минуты из секунд
	 * @param integer $secondsCount
	 * @return integer
	 */
	public static function getMinutesFromSeconds($secondsCount)
	{
		return $secondsCount / 60;
	}

    /**
     * @param String $date
     * @return false|int
     */
    public static function transformStringToTimestamp(String $date)
    {
        $separators = ['.', '-', ' '];
        foreach ($separators as $separator) {
            if (strpos($date, $separator) !== false) {
                list($day, $month, $year) = explode($separator, $date);
                $year = intval($year);
                $month = intval($month);
                $day = intval($day);

                $format = '';
                $newDate = '';

                if (!empty($year)) {
                    if (is_numeric($year)) {
                        if ($year <= 31) {
                            list($year, $day) = array($day, $year);
                        }
                        if ($year > 1000) {
                            $format = 'Y';
                            $newDate = strval($year);
                        }
                    }
                }
                if (!empty($month)) {
                    if (is_numeric($month) && $month <= 12) {
                        if (!empty($format)) {
                            $format .= '-m';
                            $newDate .= '-' . strval($month);
                        } else {
                            $format = 'm';
                            $newDate = strval($month);
                        }
                    }
                }
                if (!empty($day)) {
                    if (is_numeric($day) && $day <= 31) {
                        if (!empty($format)) {
                            $format .= '-d';
                            $newDate .= '-' . strval($day);
                        } else {
                            $format = 'd';
                            $newDate .= '-' . strval($day);
                        }
                    }
                }

                $convertedFormat = DateTime::createFromFormat('Y-m-d', $newDate);

                return strtotime($convertedFormat->format('Y-m-d'));
            }
        }
        return strtotime($date . '-01-01');
    }
}