<?php

namespace Audentio\Timer;

use Audentio\Timer\Exceptions\TimerAlreadyEndedException;
use Audentio\Timer\Exceptions\TimerAlreadyStartedException;
use Audentio\Timer\Exceptions\TimerNotEndedException;
use Audentio\Timer\Exceptions\TimerNotStartedException;

class Timer
{
    private ?float $limitInSeconds = null;
    private float $startTime;
    private float $endTime;

    private bool $isStarted = false;
    private bool $isEnded = false;

    private Duration $finalDuration;

    public function getStartTime(): float
    {
        if (!$this->isStarted()) {
            $this->throwTimerNotStartedException();
        }

        return $this->startTime;
    }

    public function getEndTime(): float
    {
        if (!$this->isEnded()) {
            $this->throwTimerNotEndedException();
        }

        return $this->endTime;
    }

    public function getCurrentOrEndTime(): float
    {
        if ($this->isEnded()) {
            return $this->getEndTime();
        }

        return microtime(true);
    }

    public function getDuration(): Duration
    {
        if (!$this->isStarted) {
            $this->throwTimerNotStartedException();
        }

        if ($this->isEnded()) {
            return $this->finalDuration;
        }

        return $this->getCurrentDuration();
    }

    public function isStarted(): bool
    {
        return $this->isStarted;
    }

    public function isEnded(): bool
    {
        return $this->isEnded;
    }

    public function hasExceededLimit(): bool
    {
        if ($this->isEnded()) {
            $this->throwTimerAlreadyEndedException();
        }

        if ($this->limitInSeconds === null) {
            return false;
        }

        $duration = $this->getCurrentDuration(true);
        if ($duration >= $this->limitInSeconds) {
            return true;
        }

        return false;
    }

    public function restart(): void
    {
        if (!$this->isStarted()) {
            $this->throwTimerNotStartedException();
        }

        $this->startTime = microtime(true);
        $this->isStarted = true;
        $this->isEnded = false;
        unset($this->endTime);
    }

    public function start(): void
    {
        if ($this->isStarted()) {
            throw new TimerAlreadyStartedException('The timer has already been started. Call ' .
                'Timer::restart() to restart it.');
        }

        $this->startTime = microtime(true);
        $this->isStarted = true;
    }

    public function end(): Duration
    {
        if (!$this->isStarted()) {
            $this->throwTimerNotStartedException();
        }

        if ($this->isEnded()) {
            $this->throwTimerAlreadyEndedException();
        }

        $this->finalDuration = $this->getCurrentDuration();
        $this->isEnded = true;
        $this->endTime = microtime(true);

        return $this->finalDuration;
    }

    private function getCurrentDuration(bool $returnAsFloat = false): Duration|float
    {
        if (!$this->isStarted()) {
            $this->throwTimerNotStartedException();
        }

        $duration = microtime(true) - $this->getStartTime();

        if ($returnAsFloat) {
            return $duration;
        }

        return new Duration($duration);
    }

    private function throwTimerAlreadyEndedException(): void
    {
        throw new TimerAlreadyEndedException('The timer has already been ended. Call Timer::restart() to ' .
            'restart it.');
    }

    private  function throwTimerNotEndedException(): void
    {
        throw new TimerNotEndedException('The timer has not been ended, Timer::end() must be called.');
    }

    private function throwTimerNotStartedException(): void
    {
        throw new TimerNotStartedException('No timer has been started, Timer::start() must be called if the ' .
            'timer isn\'t set to automatically start.');
    }

    public function __construct(null|float|int $limitInSeconds = null, bool $startTimerImmediately = true)
    {
        $this->limitInSeconds = (float) $limitInSeconds;

        if ($startTimerImmediately) {
            $this->start();
        }
    }
}