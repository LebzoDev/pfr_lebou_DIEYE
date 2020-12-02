<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Repository\ProfilRepository;
use App\DataFixtures\F1ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class F2UserFixtures extends Fixture
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
            $user = new User();
            $password =$this->encoder->encodePassword($user,"passer");
            $profil = new Profil();
            if ($i<=2) {
                $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_ADMINISTRATEUR_REFERENCE));
            }elseif($i<=4){
                $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_FORMATEUR_REFERENCE));
            }elseif($i<=6){
                $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_CM_REFERENCE));
            }else{
                $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_APPRENANT_REFERENCE));
            }
            $user->setPrenom($faker->firstName)
                 ->setNom($faker->lastName)
                 ->setUsername($faker->userName)
                 ->setPassword($password)
                 ->setArchive(false);
                 //->setPhoto($faker->image($dir = '/tmp', $width = 640, $height = 480));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
