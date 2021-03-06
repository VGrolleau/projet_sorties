<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $date = $faker->dateTimeBetween('-10 days', '+1 month');
        $start_date_clone = clone $date;
        for ($nbEvents = 1; $nbEvents <= 50; $nbEvents++)
        {
            $event = new Event();
            $event->setName('Event'. $nbEvents);
            $event->setStartDate($faker->dateTimeBetween('+9 days', '+15 day'));
            $event->setDuration($faker->numberBetween(1,999));
            $event->setRegistrationLimitDate($faker->dateTimeBetween('+5 days', '+8 day'));
            $event->setMaxRegistrations($faker->numberBetween(1,10));
            $event->setInfos($faker->text('200'));
            $event->setCreationDate($faker->dateTimeBetween('-15 days', '+1 day'));
            $event->setOrganizer($this-> getReference('user_'.$faker->numberBetween(1,50)));
            $event->setCampus($this-> getReference('campus_'.$faker->numberBetween(1,5)));
            $event->setEventState($this-> getReference('eventState_'.$faker->numberBetween(1,3)));
            $event->setLocation( $this-> getReference('location_'.$faker->numberBetween(1,50)));
            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
       return[
           UsersFixtures::class,
           CampusFixtures::class,
           EventStateFixtures::class,
           LocationFixtures::class
       ];
    }
}
