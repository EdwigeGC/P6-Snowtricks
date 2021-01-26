<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $user= new User();
            $user   ->setLastName("User n°$i")
                    ->setUsername("Username n°$i")
                    ->setEmail("email$i@mail.fr")
                    ->setPassword("User$i");

            $manager->persist($user);
        }
                
        $manager->flush();
    }
}