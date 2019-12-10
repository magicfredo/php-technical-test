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
     * @return array
     */
    public function calculatedAverages(
        int $distance,
        int $duration
    ): array {
        $averageSpeed = ($distance / $duration) * 3.6;
        $averageSpeed = round($averageSpeed, 2);

        $tmp = 60 / $averageSpeed;
        $averagePaceMinute = floor($tmp);
        $averagePaceSecond = ($tmp - $averagePaceMinute) * 60;
        $averagePace = round($averagePaceMinute . '.' . $averagePaceSecond , 2);

        return [$averageSpeed, $averagePace];
    }
}