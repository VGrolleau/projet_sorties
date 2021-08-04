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
                'name' => "Créé",
            ],
            2 => [
                'name' => "Ouvert",
                ],
            3 => [
                'name' => "Annulé",
                ],
            4 => [
                'name' => "En cours",
                ],
            5 => [
                'name' => "Terminé",
                ],
            6 => [
                'name' => "Fermé",
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
