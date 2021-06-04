<?php

namespace App\Controller;

use App\Entity\Bets;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/settings", name="user_settings")
     */
    public function settings(): Response
    {
        return $this->render('user/settings.html.twig', [
            'controller_name' => 'UserControllerSettings',
        ]);
    }

    /**
     * @Route("/user/results", name="user_results")
     */
    public function results(): Response
    {
	$results = $this->getDoctrine()
	    ->getRepository(Bets::class)
	    ->getResults();
	
        return $this->render('user/results.html.twig', [
	    'results' => $results
        ]);
    }

    /**
     * @Route("/user/details/{id}", name="user_results_details")
     */
    public function userResultsDetails(int $id): Response
    {
	$userInfo = $this->getDoctrine()
	    ->getRepository(Users::class)
	    ->findOneBy(['id' => $id]);
    
	$details = $this->getDoctrine()
	    ->getRepository(Bets::class)
	    ->getUserResultsDetails($id);

	$points = $this->getDoctrine()
	    ->getRepository(Bets::class)
	    ->sumUserPoints($id);

        return $this->render('user/details.html.twig', [
	    'userInfo' => $userInfo,
	    'details' => $details,
	    'points' => $points
        ]);
    }
}
