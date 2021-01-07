<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GroupeCompetencesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    private $repoGroupeCompetence;
    private $manager;
    private $repoCompetence;
    public function __construct(GroupeCompetencesRepository $repoGroupeCompetence,EntityManagerInterface $manager,CompetenceRepository $repoCompetence){
        $this->repoGroupeCompetence = $repoGroupeCompetence;
        $this->manager = $manager;
        $this->repoCompetence = $repoCompetence;
    }
    /**
     * @Route("/api/admin/competences", name="addCompetence", methods={"POST"})
     */
    public function addCompetence(Request $request)
    {
        $tabCheck=['idgroupcompetence','libelle','descriptif','libelle1','groupDac1','critereDev1','libelle2','groupDac2','critereDev2','libelle3','groupDac3','critereDev3'];
        $data = json_decode($request->getContent());
        $valide = true;
        foreach ($tabCheck as $key => $value) {
           if (!isset($data->$value)) {
              $valide = false;
              $chaine="probleme tableau";
           }
        }
        $id = $data->idgroupcompetence;
        $grpcomptence = $this->repoGroupeCompetence->findOneBy(['id'=>$id]);
        if (!($grpcomptence)) {
            $valide = false;
            $value = gettype($data->idgroupcompetence);
            $chaine="probleme id group competence".$id;
        }

        if($valide){
            $competence = new Competence();
            $competence->setLibelle($data->libelle)
                       ->setDescriptif($data->descriptif);
            $competence->addGroupcompetence($grpcomptence);
            for ($i=1; $i <=3 ; $i++) { 
                $niveau = new Niveau();
                $key_libelle = 'libelle'.$i;
                $key_groupDac = 'groupDac'.$i;
                $key_critereDev = 'critereDev'.$i;
                $niveau->setLibelle($data->$key_libelle);
                $niveau->setCritereDevaluation($data->$key_critereDev);
                $niveau->setGroupDactions($data->$key_groupDac);
                $this->manager->persist($niveau);
                $competence->addNiveau($niveau);
            }
            $this->manager->persist($competence);
            $this->manager->flush();
            return $this->json(['message'=>'success'],Response::HTTP_OK);
        }else {
            return $this->json(['message'=>'Veuillez remplir toutes les données et respecter les champs'.$chaine],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/admin/competences/{id}", name="modifyCompetence", methods={"PUT"})
     */
    public function modifyCompetence(Request $request,$id)
    {
        $tabCheck=['action','idgroupcompetence','libelle','descriptif','libelle1','groupDac1','critereDev1','libelle2','groupDac2','critereDev2','libelle3','groupDac3','critereDev3'];
        $data = json_decode($request->getContent());
        $valide = true;
        foreach ($tabCheck as $key => $value) {
           if (!isset($data->$value)) {
              $valide = false;
           }
        }
        
        $grpcomptence = $this->repoGroupeCompetence->findOneBy(['id'=>$data->idgroupcompetence]);
        $competence = $this->repoCompetence->findOneBy(['id'=>$id]);
        if (!isset($grpcomptence,$competence)) {
            $valide = false;
        }
        //Niveau de verification de validation de la requete
        if($valide)
        {
            //Si le cas d'une modification d'une competence
            if ($data->action=='put')
            {    
                $competence->setLibelle($data->libelle)
                           ->setDescriptif($data->descriptif)
                           ->addGroupcompetence($grpcomptence);               
                //Delete the currents "niveaux"
                $niveaux = $competence->getNiveaux();
                foreach ($niveaux as $niveau) {
                    $competence->removeNiveau($niveau);
                }
                //Add new updating "niveaux"
                for ($i=1; $i <=3 ; $i++) { 
                    $niveau = new Niveau();
                    $key_libelle = 'libelle'.$i;
                    $key_groupDac = 'groupDac'.$i;
                    $key_critereDev = 'critereDev'.$i;
                    $niveau->setLibelle($data->$key_libelle);
                    $niveau->setCritereDevaluation($data->$key_critereDev);
                    $niveau->setGroupDactions($data->$key_groupDac);
                    $this->manager->persist($niveau);
                    $competence->addNiveau($niveau);
                }
                $this->manager->persist($competence);
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Update Sucesss'],Response::HTTP_OK);
            }
            //Si le cas d'une modification d'une competence
            elseif($data->action = 'delete')
            {
                $competence->removeGroupcompetence($grpcomptence);
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Delete Sucesss'],Response::HTTP_OK);
            }
        }
        //Non validation de la requete
        else
        {
            return $this->json(['message'=>'Veuillez remplir toutes les données et respecter les champs'],Response::HTTP_NOT_FOUND);
        }
    }
}
