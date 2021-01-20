<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {
            $trick= new Trick();
            $trick  ->setName("Trick n°$i")
                    ->setDescription("<p>Description de la trick n°$i</p>")
                    ->setCreationDate(new \DateTime());
        
            $manager->persist($trick);
        }
        $manager->flush();

    }
}
