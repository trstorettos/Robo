<?php
namespace Robo\Common;

class TimeKeeper
{
    const MINUTE = 60;
    const HOUR = 3600;
    const DAY = 86400;

    protected $startedAt;
    protected $finishedAt;

    public function start()
    {
        if ($this->startedAt) {
            return;
        }
        // Get time in seconds as a float, accurate to the microsecond.
        $this->startedAt = microtime(true);
    }

    public function stop()
    {
        $this->finishedAt = microtime(true);
    }

    public function elapsed()
    {
        $finished = $this->finishedAt ? $this->finishedAt : microtime(true);
        if ($finished - $this->startedAt <= 0) {
            return null;
        }
        return $finished - $this->startedAt;
    }

    public static function formatDuration($duration)
    {
        if ($duration >= self::DAY * 2) {
            return gmdate('z \d\a\y\s H:i:s', $duration);
        }
        if ($duration > self::DAY) {
            return gmdate('\1 \d\a\y H:i:s', $duration);
        }
        if ($duration > self::HOUR) {
            return gmdate("H:i:s", $duration);
        }
        if ($duration > self::MINUTE) {
            return gmdate("i:s", $duration);
        }
        return round($duration, 3).'s';
    }
}
