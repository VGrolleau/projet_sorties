<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LocationFixtures extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbLocation = 1; $nbLocation <= 50; $nbLocation++)
        {
            $location = new Location();
            $location->setName('Location'. $nbLocation);
            $location->setStreet($faker->streetAddress);
            $location->setLatitude($faker->latitude);
            $location->setLongitude($faker->longitude);
            $location->setCity($this-> getReference('city_'.$faker->numberBetween(1,50)));

            $manager->persist($location);

            $this->addReference('location_'. $nbLocation, $location);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
       return[
         CityFixtures::class
       ];
    }
}
