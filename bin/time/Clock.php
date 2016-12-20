<?php
namespace Time;

/**
 * 時計
 *
 * システム内では、時刻はここから取得する
 * DateTimeImmutable がPHP5.5のバージョンによってbuggyなので敢えてDateTimeを使っている
 * @author y.kushida
 * @version 1.1.1
 * @uses >= php-5.5
 */
class Clock {
    /**
     * 現在時刻を返す
     * @return \DateTime
     */
    public function getTime() {
        return new \DateTime();
    }

    /**
     * 現在時刻を返す
     * @return int
     */
    public function getTimeAsInt() {
        $d = $this->getTime();
        return $d->getTimestamp();
    }

    /**
     * scriptの開始時刻を返す
     * @return \DateTime
     */
    public function getRequestTime() {
        $intUnixTime = $this->getRequestTimeAsInt();
        $result = new \DateTime('@'.$intUnixTime);
        $timeZone = new \DateTimeZone(date_default_timezone_get());
        $result = $result->setTimeZone($timeZone);
        return $result;
    }
    /**
     * scriptの開始時刻を返す。
     * テスト時はこの値をMockingするとよい。
     * @return int
     */
    public function getRequestTimeAsInt() {
		return $_SERVER['REQUEST_TIME'];
    }

	public function getToday() {
		return new \DateTime($this->getRequestTime()->format('Y-m-d'));
	}

	public function getTodayAsInt() {
		return strtotime($this->getToday()->format('Y-m-d'));
	}
}
