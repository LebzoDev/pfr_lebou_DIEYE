<?php

namespace App\Controller;

use App\Entity\GroupeCompetences;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GroupeCompetencesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetencesController extends AbstractController
{
    private $repoCompetence;
    private $repoGrpCompetence;
    private $manager;
    public function __construct(EntityManagerInterface $manager,CompetenceRepository $repoCompetence,GroupeCompetencesRepository $repoGrpCompetence){
        $this->repoCompetence = $repoCompetence;
        $this->repoGrpCompetence = $repoGrpCompetence;
        $this->manager = $manager;;
    }

    /**
     * @Route("/api/admin/group_competences", name="addGroupCompetence", methods={"POST"})
     */
    public function addGroupCompetence(Request $request){
        $tabCheck=['idcompetence','libelle','descriptif'];
        $data = json_decode($request->getContent());
        $valide = true;
        foreach ($tabCheck as $key => $value) {
           if (!isset($data->$value)) {
              $valide = false;
              $chaine='blem de tableau';
           }
        }
        
        $comptence = $this->repoCompetence->findOneBy(['id'=>$data->idcompetence]);
        if (!isset($comptence)) {
            $valide = false;
            $chaine='blem de competence';
        }
        if($valide)
        {
            $grpcompetence = new GroupeCompetences();
            $grpcompetence->setLibelle($data->libelle)
                          ->setDescriptif($data->descriptif)
                          ->addCompetence($comptence);
            $this->manager->persist($grpcompetence);
            $this->manager->flush();
            return $this->json(['etat'=>'success','message'=>'Groupe de competence ajouté avec Succes'],Response::HTTP_OK);
        }else{
            return $this->json(['message'=>'Veuillez revoir les données et respecter les champs'.$chaine],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/admin/groupe_competences/{id}", name="putGroupCompetences", methods={"PUT"})
     */
    public function putGroupController(Request $request,$id)
    {
        $tabCheck=['action','idcompetence','libelle','descriptif'];
        $data = json_decode($request->getContent());
        $valide = true;
        foreach ($tabCheck as $key => $value)
        {
           if (!isset($data->$value) && $data->action!=='put')
           {
              $valide = false;
           }
        }
        
        $competence = $this->repoCompetence->findOneBy(['id'=>$data->idcompetence]);
        $grpcompetence = $this->repoGrpCompetence->findOneBy(['id'=>$id]);
        if (!isset($grpcompetence,$competence) && $data->action!=='put')
        {
            $valide = false;
        }
        if ($data->action=='put')
        {
           if (!isset($data->libelle, $data->descriptif))
           {
               $valide=false;
           }
        }
        //Dans le cas d'archivage d'un goupe de competence
        elseif ($data->action=='archive') {
            $valide = true;
        }
        //Niveau de verification de validation de la requete
        if($valide)
        {
            //Si le cas d'une modification d'une competence
            if ($data->action=='put')
            {
                $grpcompetence->setLibelle($data->libelle)
                              ->setDescriptif($data->descriptif);
                $this->manager->persist($grpcompetence);
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Update Sucesss'],Response::HTTP_OK);
            }
            elseif ($data->action=='add')
            {
                $grpcompetence->setLibelle($data->libelle)
                              ->setDescriptif($data->descriptif)
                              ->addCompetence($competence);
                $this->manager->persist($grpcompetence);
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Ajout Success'],Response::HTTP_OK);
            }
            //Dans le cas où l'on veut supprimer une competence du groupe
            elseif ($data->action=='delete') {
                $grpcompetence->removeCompetence($competence);
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Delete Success'],Response::HTTP_OK);
            }
            //Dans le cas ou l'on veut archiver un groupe de competence
            elseif ($data->action=='archive') {
                $grpcompetence->setArchive(true);
                $competences = $grpcompetence->getCompetences();
                foreach ($competences as $competence) {
                    $grpcompetence->removeCompetence($competence);
                }
                $this->manager->flush();
                return $this->json(['etat'=>'success','message'=>'Archive Success'],Response::HTTP_OK);
            }
        }
        else
        {
            return $this->json(['message'=>'Veuillez remplir toutes les données et respecter les champs'],Response::HTTP_NOT_FOUND);
        }
    }
}
