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
        ];
    }

    public function getPointsClass(int $points): string
    {
        return "points-" . $points;
    }
}
