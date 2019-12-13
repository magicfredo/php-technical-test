<?php

namespace App\Controller;

use App\Entity\Run;
use App\Entity\User;
use App\Form\RunType;
use App\Repository\RunRepository;
use App\Service\RunService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RunController extends AbstractController
{
    /**
     * @var RunService
     */
    private $runService;

    /**
     * @var RunRepository
     */
    private $runRepository;

    /**
     * @param RunService $runService
     * @param RunRepository $runRepository
     */
    public function __construct(
        RunService $runService,
        RunRepository $runRepository
    ) {
        $this->runService = $runService;
        $this->runRepository = $runRepository;
    }

    /**
     * @Route("/run", name="run_index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(
        Request $request
    ): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        if (null === $user || !$user->getId()) {
            return new RedirectResponse($this->generateUrl('default_index'));
        }

        $runsList = $this->runService->findRunsList($request, $user->getId());

        return $this->render('/run/index.html.twig', [
            'runs' => $runsList
        ]);
    }

    /**
     * @Route("/run/form", name="run_form")
     *
     * @param Request $request
     * @param RunService $runService
     * @return RedirectResponse|Response
     */
    public function formAction(
        Request $request,
        RunService $runService
    )
    {
        /* @var User $user */
        $user = $this->getUser();

        if (null === $user || !$user->getId()) {
            return new RedirectResponse($this->generateUrl('default_index'));
        }

        $runId = $request->get('id') ? (int)$request->get('id') : null;
        $run = null !== $runId ? $this->runRepository->find($runId) : null;
        $run = $run ?? new Run();

        if (null !== $run->getId()) {
            $run->setDistance(round($run->getDistance() / 1000, 2));

            $durationTimestamp = $runService->durationTimestamp($run->getDuration());
            $run->setDuration($durationTimestamp);
        }

        $form = $this->createForm(RunType::class, $run);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $request->get('run');

            $run->setUser($user);

            $durationHour = $params['duration']['hour'] ? sprintf('%02d', $params['duration']['hour']) : '00';
            $durationMinute = $params['duration']['minute'] ? sprintf('%02d', $params['duration']['minute']) : '00';
            $durationSecond = $params['duration']['second'] ? sprintf('%02d', $params['duration']['second']) : '00';
            $duration = $durationHour * 3600 + $durationMinute * 60 + $durationSecond;
            $run->setDuration($duration);

            $distance = round((float)$params['distance'] * 1000, 2);
            $run->setDistance($distance);

            [$averageSpeed, $averagePace] = $runService->calculatedAverages($distance, $duration);
            $run->setAverageSpeed($averageSpeed);
            $run->setAveragePace($averagePace);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($run);
            $entityManager->flush();

            return $this->redirectToRoute('run_index');
        }

        return $this->render('/run/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/run/delete", name="run_delete")
     *
     * @param Request $request
     * @return Response
     */
    public function deleteAction(
        Request $request
    ): Response
    {
        $runId = (int)$request->get('id');
        $run = $this->runRepository->find($runId);

        if (null !== $run && $run->getId()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($run);
            $entityManager->flush();
        }

        return new RedirectResponse($this->generateUrl('run_index'));
    }
}
