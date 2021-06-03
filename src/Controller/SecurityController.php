<?php

namespace App\Controller;

use App\Entity\Users;
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
	if($this->getUser()) {
	    return $this->redirectToRoute('user');
	}

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="user_register", methods={"GET"})
     */
    public function register(): Response
    {
	if($this->getUser()) {
	    return $this->redirectToRoute('user');
	}

        return $this->render('security/register.html.twig', []);
    }

    /**
     * @Route("/register", name="user_regiser_action", methods={"POST"})
     */
    public function registerAction(Request $request, HelperServices $helperServices, \Swift_Mailer $mailer): Response
    {
    	$em = $this->getDoctrine()->getManager();

	if($this->getUser()) {
	    return $this->redirectToRoute('user');
	}

	$email = $helperServices->proper($request->get("email"));
	$login = $helperServices->proper($request->get("login"));
	$password1 = $helperServices->proper($request->get("password1"));
	$password2 = $helperServices->proper($request->get("password2"));

	if($email && $login && $password1 && $password2){
	    $check_user_exists = $this->getDoctrine()
		->getRepository(Users::class)
		->checkUsersExists($email, $login);
	
	    if($check_user_exists){
		$this->addFlash('error', "Prawdopodobnie istnieje już konto o podanym adresie e-mail lub loginie!");
	    }else{
		if($password1 != $password2){
		    $this->addFlash('error', "Podane hasła nie są takie same!");
		}else{
		    $newPassword = $helperServices->getHashedPassword($login, $password1);
		    $user = new Users();
		    $user->setEmail($email);
		    $user->setLogin($login);
		    $user->setPassword($newPassword);
		    $user->setCreated(new \DateTime());
		    $em->persist($user);
		    $em->flush();

		    if($user->getId()){
			$typerEmail = $this->getParameter("base_email");
		
			$message = (new \Swift_Message('Rejestracja konta w typerporemba.pl!'))
			    ->setFrom($typerEmail)
			    ->setTo($email)
			    ->setBody($this->renderView(
				    "emails/register.html.twig"
				),
				'text/html'
			    );

			$mailer->send($message);
			$this->addFlash('success', "Konto zostało utworzone!");
		    };
		}
	    }
	}else{
	    $this->addFlash('error', "Nie podano wszystkich danych!");
	}
	
	return $this->redirectToRoute('user_register');
    }

    /**
     * @Route("/reset-password", name="user_reset_password", methods={"GET"})
     */
    public function resetPassword(): Response
    {
	if($this->getUser()) {
	    return $this->redirectToRoute('user');
	}

        return $this->render('security/reset_password.html.twig', []);
    }

    /**
     * @Route("/reset-password", name="user_reset_password_action", methods={"POST"})
     */
    public function resetPasswordAction(Request $request, HelperServices $helperServices, \Swift_Mailer $mailer): Response
    {
    	$em = $this->getDoctrine()->getManager();

	if($this->getUser()) {
	    return $this->redirectToRoute('user');
	}
	
	$search = $helperServices->proper($request->get("search"));

	if($search){
	    $check_user_exists = $this->getDoctrine()
		->getRepository(Users::class)
		->checkUsersExists($search, $search);
	
	    if($check_user_exists){
		
	    }
	}

	$this->addFlash('success', "W przypadku istnienia konto o podanym adresie e-mail lub loginie została wysłana wiadomość mailowa! Sprawdź swoją skrzynkę!");

	return $this->redirectToRoute('user_reset_password');
    }
    
    /**
     * @Route("/user/change-password", name="user_change_password", methods={"POST"})
     */
    public function changePassword(Request $request, HelperServices $helperServices): Response
    {
	$em = $this->getDoctrine()->getManager();
	$password1 = $helperServices->proper($request->get("password1"));
	$password2 = $helperServices->proper($request->get("password2"));
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
