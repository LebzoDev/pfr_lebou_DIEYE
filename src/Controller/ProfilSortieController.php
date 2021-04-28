<?php

namespace App\Controller;

use App\Repository\PromoRepository;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
     private $repoPromo;
     private $repoPDS;
    public function __construct(PromoRepository $repoPromo,ProfilSortieRepository $repoPDS){
        $this->repoPromo = $repoPromo;
        $this->repoPDS = $repoPDS;
    }
    /**
     * @Route("/admin/promo/{id}/profilsorties", name="Affiche_ProfilSortie_Promo", methods={"GET"})
     */
    public function showAppPromoProfilSortie($id,SerializerInterface $serialize)
    {
        $promo = $this->repoPromo->findOneBy(['id'=>$id]);
        if(!isset($promo)){
            return $this->json(["message"=>"Ce promo n'existe pas."],Response::HTTP_NOT_FOUND);
        }
        $profilSorties = $this->repoPDS->findAll();
        $apprenants = $promo->getApprenants();
        if(isset($profilSorties)){
            $tab = $serialize->normalize($profilSorties,null,['groups'=>'affiche']);
            $i=0;
            foreach ($profilSorties as $ps) {
                foreach ($apprenants as $app) {
                    $id2 = $app->getProfilSortie()->getId();
                    if (isset($id2)) {
                        if ($ps->getId()==$id2){
                            $tab[$i]['apprenants'][]=$app;
                        }
                    }
                }
            $i++;
            }
            return $this->json($tab,200,[],['groups'=>'affiche']);
        }
    }

     /**
     * @Route("/promo/{id}/profilsortie/{idp}", name="Affiche_Apprenant_ProfilSortie_Promo", methods={"GET"})
     */
    public function afficheApprenantsProfilSortiePromo($id,$idp,SerializerInterface $serialize)
    {      
        $profilSortie=$this->repoPDS->findOneBy(["id"=>$idp]);
        $tab=[];
        $promo=$this->repoPromo->findOneBy(["id"=>$id]);
        if (isset($promo)) {
            if (isset($profilSortie)) {
                foreach ($profilSortie->getApprenants() as $app) {
                    if ($app->getPromo()->getId()==$promo->getId()) {
                        $tab[]=$app;
                    }
                }
                return $this->json($tab,Response::HTTP_OK,[],['groups'=>['affiche']]);
            }else {
                return $this->json(["message"=>"Ce profil de sortie n'existe pas dans cette promo."],Response::HTTP_NOT_FOUND);
            }
        }else {
            return $this->json(["message"=>"Cette promo n'existe pas."],Response::HTTP_NOT_FOUND);
        }

        
    }
}
