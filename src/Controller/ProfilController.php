<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $em;
    private $repoProfil;
    public function __construct(EntityManagerInterface $em, ProfilRepository $repoProfil){
    $this->em=$em;
    $this->repoProfil = $repoProfil;
    }
   
    /**
     * @Route("/admin/profils/{id}/archive", name="archive_profil", methods={"PUT"})
     *
     * @param Profil $data
     * @return void
     */
    public function archive_profil($id)
    {
        $profil = $this->repoProfil->findOneBy(['id'=>$id]);
        if(isset($profil)){
            $profil = $profil->getArchive();
            if ($data==false) {
                $profil_A_Archive->setArchive(true);
            }else{
                $profil_A_Archive->setArchive(false);
            }
            $this->em->flush();
            return $this->json($profil,200,[]);
        }else{
            return $this->json(['message'=>'Veuillez revoir votre identifiant'],404);
        }
    }

    /**
     * @Route("/admin/profils", name="get_profils", methods={"GET"})
     *
     * @return void
     */
    public function get_profils(){
        $profils = $this->repoProfil->findAll();
        if (isset($profils)) {
            return $this->json($profils,200,[],['groups'=>'show_profils']);
        }
    }

    /**
     * @Route("/admin/profils/{id}", name="get_profil", methods={"GET"})
     *
     * @return void
     */
    public function get_profil($id){
        $profils = $this->repoProfil->findOneBy(['id'=>$id]);
        if (isset($profils)) {
            return $this->json($profils,200,[],['groups'=>'show_profils']);
        }
    }

    /**
     * @Route("/admin/profils", name="post_profils", methods={"POST"})
     *
     * @param Request $request
     * @return void
     */
    public function post_profils(Request $request){

        $data = $request->getContent();
        $profil = $this->repoProfil->findOneBy(['id'=>$id]);
        if (isset($data,$profil)) {
            $tableau=json_decode($data);
            if (isset($tableau->libelle)) {
                $profil->setLibelle($tableau->libelle);
                $profil->setArchive(false);
            }
            $this->em->flush();
            return $this->json($profil,201,[]);
        }

    }


    /**
     * @Route("/admin/profils/{id}", name="put_profil", methods={"PUT"})
     *
     * @param Request $request
     * @return void
     */
    public function put_profil(Request $request,$id){

        $data = $request->getContent();
        $profil = new Profil();
        if (isset($data)) {
            $tableau=json_decode($data);
            if (isset($tableau->libelle)) {
                $profil->setLibelle($tableau->libelle);
                $profil->setArchive(false);
                $this->em->persist($profil);
            }
            $this->em->flush();
            return $this->json($profil,200,[]);
        }

    }

    /**
     *@Route("/admin/profils/{id}/users", name="get_list_user_profil", methods={"GET"})
     *
     */
    public function get_list_users_profils($id){
        $profils = $this->repoProfil->findBy(['id'=>$id]);
        if (isset($profils)) {
            return $this->json($profils,200,[],['groups'=>'show_users_profils']);
        }else{
            return $this->json(['message'=>'Veuillez revoir votre identifiant'],404);
        }
    }
}
