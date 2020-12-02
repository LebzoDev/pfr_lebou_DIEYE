<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Repository\GroupePromoRepository;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PromoController extends AbstractController
{
    private $repoPromo;
    private $repoGroupPromo;
    private $normalize;
    public function __construct(PromoRepository $repoPromo,DenormalizerInterface $normalize,GroupePromoRepository $repoGroupPromo){
        $this->repoPromo = $repoPromo;
        $this->normalize = $normalize;
        $this->repoGroupPromo = $repoGroupPromo;
    }
     /**
     * @Route("api/admin/promos", name="admin_promo", methods={"GET"})
     */
    public function admin_promo()
    {
       $promos = $this->repoPromo->findAll();
       foreach($promos as $promo){
       $groupPromos = $promo->getGroupePromos();
       $formateurs = [];
       $groupPrincipal = $this->repoGroupPromo->findOneBy(['libelle'=>'principal','id'=>$promo->getId()]);
       $formateur = $groupPrincipal->getFormateur();
       if(!in_array($formateur,$formateurs)){
            $formateurs[]= $formateur;
        }

        }
        $tab = $this->normalize->normalize($promos,null,['groups'=>["show_ref_formateur_group"]]);
        dd($tab);
        return $this->json($promos,200,[],['groups'=>["show_ref_formateur_group"]]);
    }
    /**
     * @Route("api/admin/promo/{id}/princial", name="admin_promo_principal", methods={"GET"})
     */
    public function admin_promo_principal($id)
    {
       $promos = $this->repoPromo->findOneBy(['id'=>$id]);
       /*($promos as $promo){
       $groupPromos = $promo->getGroupePromos();
       $formateurs = [];
       if(!empty($groupPromos)){
            foreach($groupPromos as $group){
                $formateur = $group->getFormateur();
                if(!in_array($formateur,$formateurs)){
                        $formateurs[]= $formateur;
                }
            }
        }
        }*/
    return $this->json($formateurs,200);
    }
     /**
     * @Route("admin/promo/apprenants/attente", name="apprenants_attente", methods={"GET"})
     */
    public function apprenants_attentes()
    {
       
    }
}
