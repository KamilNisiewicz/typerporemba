<?php

namespace App\Controller;

use App\Entity\Matches;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function index(): Response
    {
        $schedule = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->getSchedule();

        return $this->render('schedule/index.html.twig', [
            'schedule' => $schedule
        ]);
    }
}
