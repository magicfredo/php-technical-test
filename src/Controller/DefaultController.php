<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="default_index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [

        ]);
    }
}
