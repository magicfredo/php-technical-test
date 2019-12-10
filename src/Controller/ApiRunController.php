<?php

namespace App\Controller;

use App\Service\RunService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRunController extends AbstractFOSRestController implements ClassResourceInterface
{
    /**
     * @var RunService
     */
    private $runService;

    /**
     * @param RunService $runService
     */
    public function __construct(
        RunService $runService
    ) {
        $this->runService = $runService;
    }

    /**
     * @Rest\View(serializerGroups={"run", "user"})
     * @Rest\Get(path="/api/runs")
     *
     * @param Request $request
     * @return View
     */
    public function getRunsListAction(
        Request $request
    ): View {
        $runsList = $this->runService->findRunsList();

        return View::create($runsList, Response::HTTP_OK, []);
    }

    /**
     * @Rest\View(serializerGroups={"run"})
     * @Rest\Get(path="/api/users/{user_id}/runs")
     *
     * @param Request $request
     * @return View
     */
    public function getUserRunsListAction(
        Request $request
    ): View {
        $runsList = $this->runService->findRunsList($request);

        return View::create($runsList, Response::HTTP_OK, []);
    }
}