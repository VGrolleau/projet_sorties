<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $campus = [
            1 => [
                'name' => "Nantes",
            ],
            2 => [
                'name' => "Rennes",
                ],
            3 => [
                'name' => "Quimper",
                ],
            4 => [
                'name' => "Niort",
                ],
            5 => [
                'name' => "A distance",
                ]
        ];

        foreach ($campus as $key => $value){
            $campus = new Campus();
            $campus->setName($value['name']);
            $manager->persist($campus);

            $this->addReference('campus_'. $key, $campus);
        }
        $manager->flush();
    }
}
