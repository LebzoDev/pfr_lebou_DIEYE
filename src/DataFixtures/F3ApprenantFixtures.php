<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Promo;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\DataFixtures\ProfilFixtures;
use App\Repository\ProfilRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class F3ApprenantFixtures extends Fixture
{
/*
    private $encoder;
    private $repoProfil;
    public function __construct(UserPasswordEncoderInterface $encoder, ProfilRepository $repoProfil){
        $this->repoProfil = $repoProfil;
        $this->encoder = $encoder;
    }
*/
    public function load(ObjectManager $manager)
    {
/*        $faker = Factory::create("fr_FR");
        //BOUCLE POUR LES PROMOS
        for ($j=0; $j < 3 ; $j++) {
            $promo = new Promo();
            $promo->setTitre($faker->word)
                  ->setDateDebut(new \DateTime())
                  ->setDateFin(new \DateTime())
                  ->setDescription($faker->text)
                  ->setLieu($faker->city)
                  ->setReferenceAgate($faker->text(50))
                  ->setLangue($faker->randomElement(['anglais', 'français']));
            //BOUCLE POUR LES APPRENANTS            
            for ($i=1; $i <=10 ; $i++) {
                $user = new Apprenant();
                $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_APPRENANT_REFERENCE))
                     ->setPrenom($faker->firstName)
                     ->setNom($faker->lastName)
                     ->setUsername($faker->userName)
                     ->setPassword($this->encoder->encodePassword($user,"passer"))
                     ->setArchive(false)
                     ->setPromo($promo);          
                $manager->persist($user);
            }
            $manager->persist($promo);
        }
 
        $manager->flush();
        */
}

}
