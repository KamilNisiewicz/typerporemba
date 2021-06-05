<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HelperFunctions extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('points_class', [$this, 'getPointsClass']),
            new TwigFunction('result_class', [$this, 'getResultClass']),
        ];
    }

    public function getPointsClass(int $points): string
    {
        return "points-" . $points;
    }

    public function getResultClass(string $index): string
    {
	$class = "";
	
	switch ($index) {
	    case 1:
	    $class="color-gold";
	    break;
	    case 2:
	    $class="color-silver";
	    break;
	    case 3:
	    $class="color-bronze";
	    break;
	}

        return $class;
    }
}
