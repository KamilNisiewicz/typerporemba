<?php

namespace App\Controller;

use App\Entity\Bets;
use App\Entity\BetsBonus;
use App\Entity\Teams;
use App\Entity\Users;
use App\Services\HelperServices;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @Route("/user", name="user")
	 */
	public function index(): Response
	{
		return $this->render('user/index.html.twig', []);
	}

	/**
	 * @Route("/user/settings", name="user_settings")
	 */
	public function settings(): Response
	{
		return $this->render('user/settings.html.twig', []);
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
	 * @Route("/user/details/{userId}", name="user_results_details")
	 */
	public function userDetails(int $userId): Response
	{
		$userInfo = $this->getDoctrine()
			->getRepository(Users::class)
			->findOneBy(['id' => $userId]);

		$details = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatches($userId, 'finished');

		$points = $this->getDoctrine()
			->getRepository(Bets::class)
			->sumUserPoints($userId);

		$bonus = $this->getDoctrine()
			->getRepository(BetsBonus::class)
			->getUserBonuses($userId);

		if (!$userInfo) {
			return $this->redirectToRoute('user');
		}

		return $this->render('user/details.html.twig', [
			'userInfo' => $userInfo,
			'details' => $details,
			'points' => $points,
			'bonus' => $bonus
		]);
	}

	/**
	 * @Route("/user/bets", name="user_bets")
	 */
	public function bets(): Response
	{
		$user = $this->getUser();
		$userId = $user->getId();

		$finished = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatches($userId, 'finished');

		$today = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatches($userId, 'today');

		$tomorrow = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatches($userId, 'tomorrow');

		$rest = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatches($userId, 'rest');

		$bonus = $this->getDoctrine()
			->getRepository(BetsBonus::class)
			->getUserBonuses($userId);

		$teams = $this->getDoctrine()
			->getRepository(Teams::class)
			->findBy([], ['name' => 'ASC']);

		$bonusDate = $this->getParameter("bonus_date");
		$bonusDate = new \Datetime($bonusDate);

		return $this->render('user/bets.html.twig', [
			'finished' => $finished,
			'today' => $today,
			'tomorrow' => $tomorrow,
			'rest' => $rest,
			'bonus' => $bonus,
			'bonusDate' => $bonusDate,
			'teams' => $teams
		]);
	}

	/**
	 * @Route("/user/bet/{betId}", name="user_bet", methods={"GET"})
	 */
	public function userBet(int $betId): Response
	{
		$user = $this->getUser();
		$userId = $user->getId();

		$bet = $this->getDoctrine()
			->getRepository(Bets::class)
			->getUserMatch($userId, $betId);

		if ($bet) {
			return $this->render('user/bet.html.twig', [
				'bet' => $bet
			]);
		};

		return $this->redirectToRoute('user_bets');
	}

	/**
	 * @Route("/user/bet/{betId}", name="user_bet_action", methods={"POST"})
	 */
	public function userBetAction(int $betId, Request $request, HelperServices $helperServices): Response
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$userId = $user->getId();

		$homeTeam = $helperServices->proper($request->get("home_team"));
		$awayTeam = $helperServices->proper($request->get("away_team"));

		if ($userId && $betId) {
			$bet = $this->getDoctrine()
				->getRepository(Bets::class)
				->findOneBy(
					[
						'id' => $betId,
						'user' => $userId
					]
				);

			if ($bet) {
				$now = date('Y-m-d H:i:s');
				$matchDate = $bet->getMatch()->getDate()->format('Y-m-d H:i:s');

				if ($matchDate > $now) {
					if ($helperServices->checkScore($homeTeam, $awayTeam)) {
						$bet->setHomeTeamScore($homeTeam);
						$bet->setAwayTeamScore($awayTeam);
						$em->flush();
						$this->addFlash('success', 'Wynik poprawnie zmieniony!');
						$log = $now . ' User: ' . $userId . ' bet match: ' . $betId . ' : ' . $homeTeam . '-' . $awayTeam;
						file_put_contents('../var/log/bets.txt', "$log\n", FILE_APPEND);
					} else {
						$this->addFlash('error', 'Jedna z podanych liczb ma nieprawid??owy format!');
					}
				} else {
					$this->addFlash('error', 'Za p????no! Mecz ju?? si?? rozpocz????!');
				}
			} else {
				$this->addFlash('error', 'Brak meczu o takim id dla tego u??ytkownika!');
			}
		} else {
			$this->addFlash('error', 'Co?? posz??o nie tak, spr??buj jeszcze raz!');
		}

		return $this->redirectToRoute('user_bet', ['betId' => $betId]);
	}

	/**
	 * @Route("/user/bonus-bet/{betId}", name="user_bonus_bet", methods={"POST"})
	 */
	public function userBonusBet(int $betId, Request $request, HelperServices $helperServices): Response
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$userId = $user->getId();

		$champion = $helperServices->proper($request->get("champion"));
		$scorer = $helperServices->proper($request->get("scorer"));

		$bonusBet = $this->getDoctrine()
			->getRepository(BetsBonus::class)
			->getUserBonus($userId, $betId);

		$bonusDate = $this->getParameter("bonus_date");
		$bonusDate = new \Datetime($bonusDate);
		$now = date('Y-m-d H:i:s');

		if ($bonusBet && $bonusDate >= $now) {
			if ($champion) {
				$team = $this->getDoctrine()
					->getRepository(Teams::class)
					->findOneBy(['id' => $champion]);

				if ($team) {
					$bonusBet->setTeam($team);
				}
			}
			if ($scorer) {
				$bonusBet->setTopScorer($scorer);
			}

			$em->flush();
			$this->addFlash('success', 'Dane zapisane!');

			$now = date('Y-m-d H:i:s');
			$log = $now . ' User: ' . $userId . ' bet bonus: ' . $betId . ' : ' . $champion . ' - ' . $scorer;
			file_put_contents('../var/log/bonus_bets.txt', "$log\n", FILE_APPEND);
		};

		return $this->redirectToRoute('user_bets');
	}
}
