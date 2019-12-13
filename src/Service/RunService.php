<?php

namespace App\Service;

use App\Entity\Run;
use App\Repository\RunRepository;
use DateTime;
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
     * @param Request|null $request
     * @param int|null $userId
     * @return array|Run[]|null
     * @todo order, limit, offset
     *
     */
    public function findRunsList(
        ?Request $request = null,
        ?int $userId = null
    ): ?array
    {
        if (null === $userId) {
            $userId = null !== $request ? $request->get('user_id') : null;
        }
        $runsList = null;

        if (null !== $userId) {
            $runsList = $this->runRepository->findBy([
                'userId' => $userId
            ]);
        }
        else {
            $runsList = $this->runRepository->findAll();
        }

        foreach ($runsList as $run) {
            $run->setDurationFormatted(
                $this->durationFormatted($run->getDuration())
            );
        }

        return $runsList;
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

    /**
     * @param int $duration
     * @return string
     */
    public function durationFormatted(
        int $duration
    ): string {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = floor($duration % 60);

        return sprintf('%02d', $hours) . ':'
            . sprintf('%02d', $minutes) . ':'
            . sprintf('%02d', $seconds);
    }

    /**
     * @param int $duration
     * @return int
     */
    public function durationTimestamp(
        int $duration
    ): int {
        $durationFormatted = $this->durationFormatted($duration);
        $date = DateTime::createFromFormat('Y-m-d H:i:s', "1970-01-01 {$durationFormatted}");

        return $date->getTimestamp();
    }
}