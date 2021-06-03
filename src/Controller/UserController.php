<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(\Swift_Mailer $mailer): Response
    {
/*
	$message = (new \Swift_Message('Hello Email'))
	    ->setFrom('typerporemba@gmail.com')
	    ->setTo('kamilnisiewicz@op.pl')
	    ->setBody(
		"Test",
		'text/html'
	    );

	$mailer->send($message);
*/
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
}
