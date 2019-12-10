<?php

namespace App\Service;

use App\Entity\Run;
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
     * @todo order, limit, offset
     *
     * @param Request $request
     * @return array|Run[]|null
     */
    public function findRunsList(?Request $request = null): ?array
    {
        $userId = null !== $request ? $request->get('user_id') : null;
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
            dump($run);
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
}