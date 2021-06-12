<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $activeUsers = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['active' => 1]);

        $inactiveUsers = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['active' => 0]);

        return $this->render('admin/index.html.twig', [
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers
        ]);
    }
}
