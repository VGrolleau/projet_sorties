<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbUsers = 1; $nbUsers <= 50; $nbUsers++)
        {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'azerty'));
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setUsername($faker->userName);
            $user->setRoles(["ROLE_USER"]);
            $user->setPhone($faker->phoneNumber);
            $user->setIsActive('1');
            $user-> setCampus($this-> getReference('campus_'.$faker->numberBetween(1,5)));
            $user->setIsAdmin('false');
            $user->setCreatedDate($faker->dateTime('now'));

            $manager->persist($user);

            $this->addReference('user_'. $nbUsers, $user);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            CampusFixtures::class,
        ];
    }
}
