<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\ProfilSortie;
use App\Repository\ApprenantRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class F7ProfilsSortieFixtures extends Fixture
{
    private $repoApprenant;
    public function __construct( ApprenantRepository $repoApprenant){
        $this->repoApprenant = $repoApprenant;
    }
   

    public function load(ObjectManager $manager)
    {
        $array = ['Dev Front','Dev Backend','Dev Fullstack','Integrateur Web','Designer Web','Referent Digital','DataScientist','IoT'];
        $apprenants = $this->repoApprenant->findAll();
        foreach ($array as $key => $value) {

            $profilSortie = new ProfilSortie();
            $libelle = $profilSortie->setLibelleProfil($value);

            if(isset($apprenants)){
                foreach ($apprenants as $apprenant) {
                    $profilSortie->addApprenant($apprenant);
                }
            }
            
            $manager->persist($profilSortie);
        }
        $manager->flush();

    }
}
