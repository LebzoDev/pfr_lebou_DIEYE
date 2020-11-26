<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class F1ProfilFixtures extends Fixture
{
    public const PROFIL_APPRENANT_REFERENCE = 'profil_apprenant';
    public const PROFIL_ADMINISTRATEUR_REFERENCE = 'profil_admin';
    public const PROFIL_FORMATEUR_REFERENCE = 'profil_formateur';
    public const PROFIL_CM_REFERENCE = 'profil_cm';

    public function load(ObjectManager $manager)
    {
        $array = ['ADMINISTRATEUR','FORMATEUR','CM','APPRENANT'];
        foreach ($array as $key => $value) {
            $profil = new Profil();
            $profil->setLibelle($value);
            $this->addReference(self::PROFIL_.$value._REFERENCE, $profil);
            $manager->persist($profil);
        }
        $manager->flush();
    }
}
