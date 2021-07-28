<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbCity = 1; $nbCity <= 50; $nbCity++)
        {
            $city = new City();
            $city->setName($faker->city);
            $city->setZip($faker->numberBetween('01200','91550'));

            $manager->persist($city);

            $this->addReference('city_'. $nbCity, $city);
        }
        $manager->flush();
    }
}
