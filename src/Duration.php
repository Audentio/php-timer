<?php

namespace Audentio\Timer;

class Duration
{
    private int $microseconds;
    private int $milliseconds;
    private int $seconds;
    private int $minutes;
    private int $hours;

    public function getMicroseconds(): int
    {
        return $this->microseconds;
    }

    public function getMilliseconds(): int
    {
        return $this->milliseconds;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }

    public function getMinutes(): int
    {
        return $this->getMinutes();
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function __construct(float $durationSeconds)
    {
        $this->microseconds = (int) round($durationSeconds * 1000000);
        $this->milliseconds = (int) round($this->microseconds / 1000);
        $this->seconds = (int) round($this->milliseconds / 1000);
        $this->minutes = (int) round($this->seconds / 60);
        $this->hours = (int) round($this->minutes / 60);

        dump($this->microseconds);
    }
}