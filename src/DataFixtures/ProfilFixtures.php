<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
     /******************************************************************************/
     /********************************PREMIERE METHODE *****************************/
     /******************************************************************************/
    /*public function load(ObjectManager $manager)
    {

        for($i=0; $i <=3 ; $i++){ 
            $profil = new Profil();
            if ($i==0) {
                $profil->setLibelle("ADMINISTRATEUR");
            }elseif ($i==1) {
                $profil->setLibelle("FORMATEUR");
            }elseif($i==2){
                $profil->setLibelle("CM");
            }else{
                $profil->setLibelle("APPRENANT");
            }
            $manager->persist($profil);
        }
        $manager->flush();
    }
    */
     /******************************************************************************/
     /********************************DEUXIEME METHODE *****************************/
     /******************************************************************************/
    public const PROFIL_APPRENANT_REFERENCE = 'profil_apprenant';
    public const PROFIL_ADMIN_REFERENCE = 'profil_admin';
    public const PROFIL_FORMATEUR_REFERENCE = 'profil_formateur';
    public const PROFIL_CM_REFERENCE = 'profil_cm';

    public function load(ObjectManager $manager)
    {

        for($i=0; $i <=3 ; $i++){ 
            $profil = new Profil();
            if ($i==0) {
                $profil->setLibelle("ADMINISTRATEUR");
                $this->addReference(self::PROFIL_ADMIN_REFERENCE, $profil);
            }elseif ($i==1) {
                $profil->setLibelle("FORMATEUR");
                $this->addReference(self::PROFIL_FORMATEUR_REFERENCE, $profil);
            }elseif($i==2){
                $profil->setLibelle("CM");
                $this->addReference(self::PROFIL_CM_REFERENCE, $profil);
            }else{
                $profil->setLibelle("APPRENANT");
                $this->addReference(self::PROFIL_APPRENANT_REFERENCE, $profil);
            }
            $manager->persist($profil);
            
        }
        $manager->flush();

    }
}
