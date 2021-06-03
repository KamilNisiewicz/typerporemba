<?php

namespace App\Services;

class HelperServices
{

    public function checkIsPasswordValid(string $formLogin, string $formPassword, string $userPassword): bool
    {
	$hashedPassword = $this->getHashedPassword($formLogin, $formPassword);
	
	if($hashedPassword === $userPassword){
	    return true;
	}else{
	    return false;
	};
    }

    public function getHashedPassword(string $login, string $password): string
    {
	$password = md5($login . "_typer_" . $password);
	
	return $password;
    }
}
