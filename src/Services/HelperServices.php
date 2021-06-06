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

    public function generateRandomString(int $length = 10): string {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
	    $randomString .= $characters[rand(0, $charactersLength - 1)];
	}

	return $randomString;
    }
    
    public function checkScore($homeTeam, $awayTeam): bool {
	$isNumeric = is_numeric($homeTeam) && is_numeric($awayTeam);
	$homeTeamScore = $homeTeam >= 0 ? 1 : 0;
	$awayTeamScore = $awayTeam >= 0 ? 1 : 0;
	$homeTeamScoreBig = $homeTeam > 99 ? 0 : 1;
	$awayTeamScoreBig = $awayTeam > 99 ? 0 : 1;
	
	if($isNumeric && $homeTeamScore && $awayTeamScore && $homeTeamScoreBig && $awayTeamScoreBig){
	    return true;
	}else{
	    return false;
	}
    }

    public function proper($text): string
    {
	$p = [];
	$r = [];

	$p[0] = '/"/';
        $r[0] = '&quot;';
        $p[1] =  "/'/";
        $r[1] =  '&#39;';
        $p[2] = '/\*/';
        $r[2] = '';
        $p[3] = '/`/';
        $r[3] = '';
        $p[4] = '/|/';
        $r[4] = '';
        $p[5] = '/</';
        $r[5] = '&lt;';
        $p[6] = '/>/';
        $r[6] =  '&gt;';
        $p[7] = '/\{/';
        $r[7] = '&#123;';
        $p[8] = '/\}/';
        $r[8] = '&#125;';
        $p[9] = '/chr\(/';
        $r[9] = '';
	$p[10] = '/^eval\ /';
        $r[10] = '';
        $p[11] = '/system\(/';
        $r[11] = '';
        $p[12] = '/print\(/';
        $r[12] = '';
        $p[13] = '/\(/';
        $r[13] = '&#040;';
        $p[14] = '/\)/';
        $r[14] = '&#041;';
        $p[15] = '/&#8211;/';
        $r[15] = '-';

        return preg_replace($p, $r, $text);
    }

}
