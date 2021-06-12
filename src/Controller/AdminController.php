<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Entity\Users;
use App\Services\HelperServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

        $emptyMatches = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->getEmptyMatches();

        return $this->render('admin/index.html.twig', [
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'emptyMatches' => $emptyMatches
        ]);
    }

	/**
	 * @Route("/admin/match/{matchId}", name="admin_match_action", methods={"POST"})
	 */
	public function userBetAction(int $matchId, Request $request, HelperServices $helperServices): Response
	{
		$em = $this->getDoctrine()->getManager();

		$homeTeam = $helperServices->proper($request->get("home_team"));
		$awayTeam = $helperServices->proper($request->get("away_team"));

		$match = $this->getDoctrine()
			->getRepository(Matches::class)
			->findOneBy(
				['id' => $matchId]
			);
        
		if($match){
			$match->setHomeTeamScore($homeTeam);
			$match->setAwayTeamScore($awayTeam);
			$em->flush();
			$this->addFlash('success', 'Wynik zapisany!');
		}

		return $this->redirectToRoute('admin');
	}
}
