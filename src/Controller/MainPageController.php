<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index(): Response
    {
        $activeUsers = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['active' => 1]);

        $premium = $this->getParameter("premium");
        $prizePool = (count($activeUsers) * $premium);

        return $this->render('main_page/index.html.twig', [
            'prizePool' => $prizePool
        ]);
    }
}
