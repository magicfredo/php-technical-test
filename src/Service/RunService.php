<?php

namespace App\Service;

use App\Repository\RunRepository;
use Symfony\Component\HttpFoundation\Request;

class RunService
{
    /**
     * @var RunRepository
     */
    private $runRepository;

    /**
     * @param RunRepository $runRepository
     */
    public function __construct(
        RunRepository $runRepository
    ) {
        $this->runRepository = $runRepository;
    }

    /**
     * @param int $distance (meter)
     * @param int $duration (seconds)
     * @return float
     */
    public function getAverageSpeed(
        int $distance,
        int $duration
    ): float {
        $averageSpeed = ($distance / $duration) * 3.6;

        return round($averageSpeed, 2);
    }

    /**
     * @param int $distance (meter)
     * @param int $duration (seconds)
     * @return float
     */
    public function getAveragePace(
        int $distance,
        int $duration
    ): float {
        $averagePace = ($duration / 60) / ($distance / 1000);

        return round($averagePace, 2);
    }
}