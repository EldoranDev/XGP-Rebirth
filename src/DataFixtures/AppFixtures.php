<?php

namespace App\DataFixtures;

use App\Entity\Option;
use App\Entity\OptionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // TODO: All of these need to get set in the installation as well

        $manager->persist(new Option('server_name', 'XGP Rebirth', OptionType::STRING));
        $manager->persist(new Option('resource_multiplier', "1.0", OptionType::FLOAT));
        $manager->persist(new Option('universe_speed', "2500", OptionType::INT));
        $manager->persist(new Option('metal_basic_income', "90", OptionType::INT));
        $manager->persist(new Option('crystal_basic_income', "45", OptionType::INT));
        $manager->persist(new Option('deuterium_basic_income', "0", OptionType::INT));
        $manager->persist(new Option('stat_update_time', "900", OptionType::INT));

        $manager->flush();
    }
}
