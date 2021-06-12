<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegulationsController extends AbstractController
{
    /**
     * @Route("/regulations", name="regulations")
     */
    public function index(): Response
    {
        $premium = $this->getParameter("premium");

        return $this->render('regulations/index.html.twig', [
            'premium' => $premium
        ]);
    }
}
