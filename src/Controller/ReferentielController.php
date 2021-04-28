<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\CriteresReferentiel;
use App\Entity\GroupeCompetences;
use App\Entity\Referentiel;
use App\Repository\GroupeCompetencesRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielController extends AbstractController
{

    private $manager;
    private $repoReferentiel;
    private $normalize;
    private $repoGrpComp;
    public function __construct(GroupeCompetencesRepository $repoGrpComp,NormalizerInterface $normalize,EntityManagerInterface $manager,ReferentielRepository $repoReferentiel)
    {
        $this->manager = $manager;
        $this->repoReferentiel = $repoReferentiel;
        $this->normalize = $normalize;
        $this->repoGrpComp = $repoGrpComp;
    }
    /**
     * @Route("api/admin/referentiels", name="referentiel",methods={"POST"})
     */
    public function addReferentiel(Request $request, SerializerInterface $serialize)
    {
        $tabCheck=['libelle','presentation','groupCompetences','admission','evaluation'];
        $data=$request->request->all();
        $programme = $request->files->get('pdfFile');
        if (isset($programme)) {
             $programme = fopen($programme->getRealPath(),"rb");
        }

        $valide = true;
        foreach ($tabCheck as $key => $value) {
           if (!isset($data[$value])) {
              $valide = false;
           }
        }
        if(!$programme){
            $valide = false;
        }

        if($valide){
                $referentiel = new Referentiel();
                foreach (json_decode($data['admission']) as $admission) {
                    $critereRef = new CriteresReferentiel();
                    $critereRef->setLibelle($admission)
                               ->setType('admission')
                               ->setArchive(false);
                    $this->manager->persist($critereRef);
                    $referentiel->addCriteresReferentiel($critereRef);
                }
                foreach (json_decode($data['evaluation']) as $evaluation) {              
                    $critereRef = new CriteresReferentiel();
                    $critereRef->setLibelle($evaluation)
                               ->setType('evaluation')
                               ->setArchive(false);
                    $this->manager->persist($critereRef);
                    $referentiel->addCriteresReferentiel($critereRef);
                }
                $tab = json_decode($data['groupCompetences']);
                //$grouComp = $serialize->deserialize($tab[0],GroupeCompetences::class,'json');
                foreach ($tab as $groupCompetence) {
                    $grouComp =  $this->repoGrpComp->findOneBy(['id'=>$groupCompetence->id]);
                    $referentiel->addGroupeCompetence($grouComp);
                    $this->manager->persist($referentiel);
                }
                $referentiel->setLibelle($data['libelle'])
                            ->setPresentation($data['presentation'])
                            ->setProgramme($programme)
                            ->setArchive(false);
                //  while($referentiel->getProgramme()==null || $referentiel->getProgramme()==''){
                //         //$programme = stream_get_contents($programme);
                //         $referentiel->setProgramme($programme);
                // }
                $this->manager->persist($referentiel);
                $this->manager->flush();
                return $this->json(['message'=>'SUCCESS','class'=>get_class($referentiel),'Referentiel'=>$referentiel],201);

        }else{
            return $this->json(['message'=>'fAILED','error__'=>$data],401);
        }
        
    }
}
