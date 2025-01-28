<?php

namespace App\Entity;

enum PlanetType: int
{
    case Planet = 1;
    case Debris = 2;
    case Moon = 3;
}
