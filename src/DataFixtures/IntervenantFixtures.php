<?php

namespace App\DataFixtures;

use App\Entity\Intervenant;
use App\Entity\Secretaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IntervenantFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $intervenant = new Intervenant();
        $intervenant->setNom("Leclerc");
        $intervenant->setPrenom("Gauthier");
        $intervenant->setSpecialite("Web et Mobile");
        $intervenant->setEmail("leclercgauthier@planning.fr");
        $intervenant->setPassword($this->encoder->encodePassword($intervenant, "leclercgauthier"));
        $intervenant->setRoles(array("ROLE_INTERVENANT"));
        $manager->persist($intervenant);
        $manager->flush();

        $secretaire = new Secretaire();
        $secretaire->setNom("Bringer");
        $secretaire->setPrenom("Corentin");
        $secretaire->setEmail("corentinbringer@planning.fr");
        $secretaire->setPassword($this->encoder->encodePassword($intervenant, "corentinbringer"));
        $secretaire->setRoles(array("ROLE_SECRETAIRE"));
        $manager->persist($secretaire);
        $manager->flush();
    }
}