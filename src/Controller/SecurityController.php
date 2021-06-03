<?php

namespace App\Controller;

use App\Services\HelperServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/user/change-password", name="user_change_password", methods={"POST"})
     */
    public function changePassword(Request $request, HelperServices $helperServices): Response
    {
	$em = $this->getDoctrine()->getManager();
	$password1 = $request->get("password1");
	$password2 = $request->get("password2");
	$user = $this->getUser();
	$login = $user->getLogin();
	$currentPassword = $user->getPassword();
	
	if($password1 === $password2 && $login){
	    $newPassword = $helperServices->getHashedPassword($login, $password1);
	    if($newPassword === $currentPassword){
		$this->addFlash('error', "Próbujesz ustawić to samo hasło!");
	    }else{
		$user->setPassword($newPassword);
		$em->flush();
		$this->addFlash('success', "Hasło zmienione poprawnie!!");
	    };
	}else{
	    $this->addFlash('error', "Podane hasła nie są takie same!");
	}
	
	return $this->redirectToRoute('user_settings');
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
