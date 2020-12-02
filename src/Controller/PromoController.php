<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PromoController extends AbstractController
{
    private $repoPromo;
    public function __construct(PromoRepository $repoPromo,DenormalizerInterface $normalize){
        $this->repoPromo = $repoPromo;
        $this->normalize = $normalize;
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
