<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/users", name="admin_users")
     *
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function userAction(
        Request $request,
        UserService $userService
    ): Response
    {
        $users = $userService->findAll($request);

        return $this->render('admin/user.html.twig', [
            'users' => $users
        ]);
    }
}
