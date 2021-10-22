<?php

namespace App\DataFixtures;

use App\Entity\Matiere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MatiereFixtures extends Fixture
{
    public function load(ObjectManager $manager) {
        $matiere1 = new Matiere();
        $matiere1->setLibelle("JAVA");
        $manager->persist($matiere1);
        $manager->flush();

        $matiere2 = new Matiere();
        $matiere2->setLibelle("Php/MySql");
        $manager->persist($matiere2);
        $manager->flush();
    }
}