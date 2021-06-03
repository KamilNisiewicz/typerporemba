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
	$user_data = $this->getDoctrine()
	    ->getRepository(Users::class)
	    ->findOneBy(['id' => 1]);

        return $this->render('main_page/index.html.twig', [
            'controller_name' => 'MainPageController',
            'user_data' => $user_data
        ]);
    }
}
