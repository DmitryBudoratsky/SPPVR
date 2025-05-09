<?php

namespace common\components\helpers;

class DateHelper
{
    public static function getRusMonth()
    {
        return [
            '01' => "Январь",
            '02' => "Февраль",
            '03' => "Март",
            '04' => "Апрель",
            '05' => "Май",
            '06' => "Июнь",
            '07' => "Июль",
            '08' => "Август",
            '09' => "Сентябрь",
            '10' => "Октябрь",
            '11' => "Ноябрь",
            '12' => "Декабрь"
        ];
    }

    public static function getRusMonthInGenitive()
    {
        return [
            '01' => "Января",
            '02' => "Февраля",
            '03' => "Марта",
            '04' => "Апреля",
            '05' => "Мая",
            '06' => "Июня",
            '07' => "Июля",
            '08' => "Августа",
            '09' => "Сентября",
            '10' => "Октября",
            '11' => "Ноября",
            '12' => "Декабря"
        ];
    }

    /**
     * @param $time
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
	public static function startMonthDate($time)
	{
		$formatter = \Yii::$app->formatter;
		$date = $formatter->asDate($time, 'yyyy-MM-01 00:00');
		$monthStartTime = $formatter->asTimestamp($date);
		return $monthStartTime;
	}

    /**
     * @param $time
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
	public static function endMonthDate($time)
	{
		$startMonthDate = self::startMonthDate($time);
		$startMonthDate += 1.5 * 29.5 * 24 * 60 * 60;
		return self::startMonthDate($startMonthDate);
	}

    /**
     * @param $firstTime
     * @param $lastTime
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
	public static function prepareMonthlyPeriods($firstTime, $lastTime)
	{
		//TODO: refactor this using DateTime, DateInterval
		$data = [];
	    if (!empty($firstTime) && !empty($lastTime)) {
	    	for (
	    		$timestamp = $lastTime;
	    		$timestamp >= $firstTime;
	    		$timestamp = self::startMonthDate($timestamp - 0.5 * 29.5 * 24 * 60 * 60)
	    	) {
	    		$from = self::startMonthDate($timestamp);
	    		$to = self::endMonthDate($timestamp);
	    		$data[] = [
		        	'time'			=> $from,
		        	'from'			=> $from,
		        	'to'			=> $to,
		        ];
	    	}
	    }

	    return $data;
	}
	
	/**
	 * Получить количество секунд на начала дня
	 * @return integer
	 */
	public static function getSecondsOnBeginDay()
	{
		$day = DateHelper::formatDate(time());
		$secondsOnBeginDay = strtotime($day);
		return $secondsOnBeginDay;
	}
	
	/**
	 * @param integer $timestamp
	 * @return string
	 */
	public static function formatDate($timestamp)
	{
		return self::formatTimeWithFormat($timestamp, "Y-m-d");
	}

    /**
     * @param $timestamp
     * @param $format
     * @return string
     * @throws \Exception
     */
	public static function formatTimeWithFormat($timestamp, $format)
	{
		if (empty($timestamp)) {
			return '';
		}
	
		date_default_timezone_set('UTC');
		$dateTime = new \DateTime();
		$dateTime->setTimestamp($timestamp);
		$dateTime->setTimeZone(new \DateTimeZone('Europe/Moscow'));
	
		return $dateTime->format($format);
	}

    /**
     * Получение массива дат по дням.
     * @param $start
     * @param $end
     * @param $format
     * @return array
     * @throws \Exception
     */
	public static function prepareDaylyPeriod($start, $end, $format)
	{
		\Yii::info("Preparing day periods: {$start}, {$end}");
		$array = [];
		$interval = new \DateInterval('P1D');
		
		$realEnd = new \DateTime($end);
		$realEnd->add($interval);
		
		$period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);
		
		foreach ($period as $date) {
			$array[] = $date->format($format);
		}
		\Yii::trace("Daily periods: " . var_export($array, true));
		return $array;
	}

    /**
     * @return string
     */
    public static function getMonthFormattedDate($timestamp)
    {
        $month = date("m", $timestamp);
        $monthRus = DateHelper::getRusMonth()[$month];
        $year = date("Y", $timestamp);
        return $monthRus . ' ' . $year . ' г.';
    }

    /**
     * @return false|int
     */
    public static function getPreviousMonthTimestamp()
    {
        $monthDate = date("Y-m-d H:i:s", strtotime("-1 month"));
        $offsetSeconds = TimeHelper::getOffsetSeconds();
        $monthTimestamp = strtotime($monthDate) + $offsetSeconds;
        return $monthTimestamp;
    }

        /**
     * @return false|int
     */
    public static function getNextMonthTimestamp()
    {
        $monthDate = date("Y-m-d H:i:s", strtotime("+1 month"));
        $offsetSeconds = TimeHelper::getOffsetSeconds();
        $timestamp = strtotime($monthDate) + $offsetSeconds;
        return $timestamp;
    }

    /**
     * @return false|int
     */
    public static function getPreviousMonthBeginTimeStamp()
    {
        $monthTimestamp = DateHelper::getPreviousMonthTimestamp();
        $monthBeginDate = date("Y-m-01", $monthTimestamp);
        $offsetSeconds = TimeHelper::getOffsetSeconds();
        $monthBeginTimeStamp = strtotime($monthBeginDate) + $offsetSeconds;
        return $monthBeginTimeStamp;
    }

    /**
     * @return false|int
     */
    public static function getNextMonthBeginTimeStamp()
    {
        $timestamp = DateHelper::getNextMonthTimestamp();
        $beginDate = date("Y-m-01", $timestamp);
        $offsetSeconds = TimeHelper::getOffsetSeconds();
        $beginTimestamp = strtotime($beginDate) + $offsetSeconds;
        return $beginTimestamp;
    }

    /**
     * @return false|int
     */
    public static function getCurrentMonthBeginTimestamp()
    {
        $monthBeginDate = date("Y-m-01", time());
        $offsetSeconds = TimeHelper::getOffsetSeconds();
        $monthBeginTimestamp = strtotime($monthBeginDate) + $offsetSeconds;
        return $monthBeginTimestamp;
    }
}