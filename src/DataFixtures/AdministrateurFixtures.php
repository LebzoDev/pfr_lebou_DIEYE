<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Repository\ProfilRepository;
use App\DataFixtures\F1ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministrateurFixtures extends Fixture
{
    private $encoder;
    private $repoProfil;
    public function __construct(UserPasswordEncoderInterface $encoder, ProfilRepository $repoProfil){
        $this->repoProfil = $repoProfil;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        
        for ($i=1; $i <=10 ; $i++) { 
            $user = new Apprenant();
            $password =$this->encoder->encodePassword($user,"passer");
            $profil = new Profil();

            $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_ADMIN_REFERENCE));
            
            $user->setPrenom($faker->firstName)
                 ->setNom($faker->lastName)
                 ->setUsername($faker->userName)
                 ->setPassword($password);
            
            $manager->persist($user);

        }

        $manager->flush();
    }
}
