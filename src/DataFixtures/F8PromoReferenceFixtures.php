<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Promo;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\GroupePromo;
use App\Entity\Referentiel;
use App\Repository\ProfilRepository;
use App\DataFixtures\F1ProfilFixtures;
use App\Entity\CriteresReferentiel;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class F8PromoReferenceFixtures extends Fixture
{
    private $encoder;
    private $repoProfil;
    private $repoFormateur;
    public function __construct(UserPasswordEncoderInterface $encoder, ProfilRepository $repoProfil,FormateurRepository $repoFormateur){
        $this->repoProfil = $repoProfil;
        $this->encoder = $encoder;
        $this->repoFormateur = $repoFormateur;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        $formateur = new Formateur();
        $formateur->setProfil($this->getReference(F1ProfilFixtures::PROFIL_FORMATEUR_REFERENCE))
        ->setPrenom($faker->firstName)
        ->setNom($faker->lastName)
        ->setUsername($faker->userName)
        ->setPassword($this->encoder->encodePassword($formateur,"passer"))
        ->setArchive(false);
        $manager->persist($formateur);
        
        //BOUCLE POUR LES REFERENTIELS
        for ($j=0; $j < 3 ; $j++) {
           $referentiel = new Referentiel();
           $referentiel->setLibelle($faker->word)
                       ->setPresentation($faker->word)
                       ->setProgramme($faker->text(50));
        //BOUCLE POUR LES CRIETERES REFERENTIELS
        $criteres_array=['admission','evaluation'];
        for ($h=0; $h < 9 ; $h++) {
            shuffle($criteres_array);
            $crit_referentiel = new CriteresReferentiel();
            $crit_referentiel->setLibelle($faker->word)
                              ->setType($criteres_array[0])
                              ->setArchive(false);
            $manager->persist($crit_referentiel);
            $referentiel->addCriteresReferentiel($crit_referentiel);
        }
                       //->setCriteres($faker->text(100));
                             
            //BOUCLE POUR LES PROMOS          
            for ($i=1; $i <=3 ; $i++) {
                $promo = new Promo();
                $promo->setTitre($faker->word)
                      ->setDescription($faker->text(50))
                      ->setLieu($faker->city)
                      ->setReferenceAgate($faker->text(100))
                      ->setLangue($faker->randomElement(['français','anglais']))
                      ->setDateDebut(new DateTime())
                      ->setDateFin(new DateTime())
                      ->setReferentiel($referentiel);

                //BOUCLE POUR LES GROUPES DE  PROMOS  
                for ($k=1; $k <=3 ; $k++) {
                    $groupPromo = new GroupePromo();
                    if($k==1){
                        $groupPromo->setNom("Groupe Principal")
                                   ->setType("principal");
                    }else{
                        $groupPromo->setNom($faker->word)
                                   ->setType("second");;
                    }
                    $groupPromo->setDateCreation(new DateTime())
                               ->setPromo($promo)
                               ->addFormateur($formateur);
                              
                    $manager->persist($groupPromo);
                }

                 //BOUCLE POUR LES APPRENANTS            
                for ($l=1; $l <=10 ; $l++) {
                    $user = new Apprenant();
                    $user->setProfil($this->getReference(F1ProfilFixtures::PROFIL_APPRENANT_REFERENCE))
                        ->setPrenom($faker->firstName)
                        ->setNom($faker->lastName)
                        ->setUsername($faker->userName)
                        ->setPassword($this->encoder->encodePassword($user,"passer"))
                        ->setArchive(false);
                    $user->setPromo($promo)
                         ->addMygroupePromo($groupPromo);
                       
                    $manager->persist($user);
                }
                $promo->addGroupePromo($groupPromo);
                $manager->persist($promo);
                }
                $manager->persist($referentiel);
        }
        $manager->flush();
}
}
