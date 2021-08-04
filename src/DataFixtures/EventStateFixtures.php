<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\EventState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventStateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $eventState = [
            1 => [
                'name' => "Created",
            ],
            2 => [
                'name' => "Open",
                ],
            3 => [
                'name' => "Canceled",
                ],
            4 => [
                'name' => "In progress",
                ],
            5 => [
                'name' => "Finished",
                ],
            6 => [
                'name' => "Closed",
            ]
        ];

        foreach ($eventState as $key => $value){
            $eventState = new EventState();
            $eventState->setName($value['name']);
            $manager->persist($eventState);

            $this->addReference('eventState_'. $key, $eventState);
        }
        $manager->flush();
    }
}
