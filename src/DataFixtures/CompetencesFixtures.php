<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Niveau;
use App\Entity\Competence;
use App\Entity\GroupeCompetences;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CompetencesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        //BOUCLE POUR LES GROUPES DE COMPETENCES
        for ($j=0; $j < 3 ; $j++) {
            $groupecompetence = new GroupeCompetences();
            $groupecompetence->setLibelle($faker->word)
                             ->setDescriptif($faker->text);
                             
            //BOUCLE POUR LES COMPETENCES           
            for ($i=1; $i <=3 ; $i++) {
                $competence = new Competence();
                $competence->setLibelle($faker->word)
                           ->setDescriptif($faker->text);

                //BOUCLE POUR LES NIVEAUX  
                for ($k=1; $k <=3 ; $k++) {
                    $niveau = new Niveau();
                    $niveau->setLibelle($faker->firstName)
                           ->setCritereDEvaluation($faker->text)
                           ->setGroupDactions($faker->text)
                           ->setCompetence($competence); 
                    $manager->persist($niveau);
                } 
                $manager->persist($competence);
                $groupecompetence->addCompetence($competence);
            }
            $manager->persist($groupecompetence);
        }
        $manager->flush();
}
}
