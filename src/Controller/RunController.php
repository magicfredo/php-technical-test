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
     * @Route("/run", name="run_index")
     *
     * @param Request $request
     * @param RunRepository $runRepository
     * @return Response
     */
    public function indexAction(
        Request $request,
        RunRepository $runRepository
    ): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        if (null === $user || !$user->getId()) {
            return new RedirectResponse($this->generateUrl('default_index'));
        }

        $runsList = $runRepository->findBy([
            'userId' => $user->getId(),
        ]) ?? [];

        return $this->render('/run/index.html.twig', [
            'runs' => $runsList
        ]);
    }

    /**
     * @Route("/run/form", name="run_form")
     *
     * @param Request $request
     * @param RunRepository $runRepository
     * @param RunService $runService
     * @return RedirectResponse|Response
     */
    public function formAction(
        Request $request,
        RunRepository $runRepository,
        RunService $runService
    )
    {
        /* @var User $user */
        $user = $this->getUser();

        if (null === $user || !$user->getId()) {
            return new RedirectResponse($this->generateUrl('default_index'));
        }

        $runId = $request->get('id') ? (int)$request->get('id') : null;
        $run = null !== $runId ? $runRepository->find($runId) : null;
        $run = $run ?? new Run();

        if (null !== $run->getId()) {
//            $hms = sprintf('%02d:%02d:%02d',
//                $run->getDuration() / 3600,
//                $run->getDuration() / 60 % 60,
//                $run->getDuration() % 60
//            );
//            $date = \DateTime::createFromFormat('H:i:s', $hms);
//            $run->setDuration($date->getTimestamp());

            $run->setDistance(round($run->getDistance() / 1000, 2));
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
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', "1970-01-01 {$durationHour}:{$durationMinute}:{$durationSecond}");
            $run->setDuration($date->getTimestamp());

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
     * @param RunRepository $runRepository
     * @return Response
     */
    public function deleteAction(
        Request $request,
        RunRepository $runRepository
    ): Response
    {
        $runId = (int)$request->get('id');
        $run = $runRepository->find($runId);

        if (null !== $run && $run->getId()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($run);
            $entityManager->flush();
        }

        return new RedirectResponse($this->generateUrl('run_index'));
    }
}
