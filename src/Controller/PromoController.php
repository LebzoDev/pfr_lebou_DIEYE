<?php

namespace App\Controller;

use DateTime;
use App\Entity\Promo;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\GroupePromo;
use App\Repository\PromoRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupePromoRepository;
use App\Repository\ReferentielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PromoController extends AbstractController
{
    private $repoPromo;
    private $manager;
    private $repoGroupPromo;
    private $normalize;
    private $repoReferentiel;
    private $serialize;
    public function __construct(EntityManagerInterface $manager,ReferentielRepository $repoReferentiel,PromoRepository $repoPromo,SerializerInterface $serialize,DenormalizerInterface $normalize,GroupePromoRepository $repoGroupPromo){
        $this->repoPromo = $repoPromo;
        $this->manager = $manager;
        $this->normalize = $normalize;
        $this->repoGroupPromo = $repoGroupPromo;
        $this->serialize = $serialize;
        $this->repoReferentiel = $repoReferentiel;
    }

    /**
     * Undocumented function
     * @Route("api/admin/promo", name="post_promo", methods={"POST"})
     * @param Request $request
     * @return void
     */
    public function post_promo(Request $request){
        $tabCheck=['idreferentiel','lieu','referenceAgate','dateDebut','dateFin','description','langue','titre'];
        
        //$data = json_decode($request->getContent());
        $data=$request->request->all();
        $data_json = json_encode($data);
        //dd($data_json);
        //dd($request->request->all(),$request->files->get('excelFile'));
        $valide = true;
        foreach ($tabCheck as $key => $value) {
           if (!isset($data[$value])) {
              $valide = false;
           }
        }
        $referentiel = $this->repoReferentiel->findOneBy(['id'=>($data['idreferentiel'])]);
        if (!isset($referentiel)) {
            $valide = false;
        }
        if($valide){
            $group_princicpal = new GroupePromo();
            $group_princicpal->setType('principal')
                             ->setNom('Groupe Principal')
                             ->setDateCreation(new DateTime);

            $promo = $this->serialize->deserialize($data_json,Promo::class,'json');
            $promo->setReferentiel($referentiel)
                  ->addGroupePromo($group_princicpal);
            $this->manager->persist($group_princicpal);
            $this->manager->persist($promo);
            $this->manager->flush();
            

            //Traitement du fichier excel
            if($request->files->get('excelFile')){
//----------------------------------------------------
        //DEBUT RECUPERATION DES DONNEES DU FICHIERS EXCELS
        //-----------------------------------------------------
        
        $doc = $request->files->get("excelFile");

        // $reader = IOFactory::createReaderForFile($doc);
        // //$reader->setReadDataOnly(true);

        $file= IOFactory::identify($doc);
        
        $reader= IOFactory::createReader($file);

        dd($file,$reader);

        $spreadsheet=$reader->load($doc);
        
        $tab_apprenants= $spreadsheet->getActivesheet()->toArray();

        $attr=$tab_apprenants[0];
        $tabrjz=[];
        for($i=1;$i<count($tab_apprenants);$i++)
        {
            $apprenant=new Apprenant();
            for($k=0;$k<count($tab_apprenants[$i]);$k++)
            {
                $data=$tab_apprenants[$i][$k];
                if($attr[$k]=="Password")
                {
                     $apprenant->setPassword($this->encoder->encodePassword($apprenant,$data));
                }else
                {
                $apprenant->{"set".$attr[$k]}($data);
                }
            }
            $apprenant->setArchive(false);
            $apprenant->setStatus('attente');
            $this->manager->persist($apprenant);
            $group_princicpal->addApprenant($apprenant);
        }

        //------------------------------------------------------
        //FIN RECUPERATION DES DONNEES DU FICHIERS EXCELS
        //-----------------------------------------------------
            }

            return $this->json($promo,200);
        }else{
            return $this->json(['message'=>'failed'],401);
        }
    }
     /**
     * @Route("api/admin/promo", name="admin_promo", methods={"GET"})
     */
    public function admin_promo()
    {
       $promos = $this->repoPromo->findAll();
       $tab = $this->normalize->normalize($promos,null,['groups'=>["show_ref_formateur_group"]]);
       foreach($promos as $key => $promo){
       $groupPrincipal = $this->repoGroupPromo->findOneBy(['nom'=>'principal','promo'=>$promo->getId()]);
       if (isset($groupPrincipal)) {
            $formateurs = $groupPrincipal->getFormateurs();
            foreach ($formateurs as $formateur) {
                $tabFormateurs[$key][]= ($this->normalize->normalize($formateur,null,['groups'=>['show_ref_formateur_group']]));
            }
            $tab[$key]['formateurs']=$tabFormateurs[$key];
       }
        }
        return $this->json($tab,200,[],['groups'=>["show_ref_formateur_group"]]);
    }
     /**
     * @Route("api/admin/promo/{id}", name="admin_promo", methods={"GET"})
     */
    public function admin_promo_item($id)
    {
       $promo = $this->repoPromo->findOneBy(['id'=>$id]);
       $tab = $this->normalize->normalize($promo,null,['groups'=>["show_ref_formateur_group"]]);
       $groupPrincipal = $this->repoGroupPromo->findOneBy(['type'=>'principal','promo'=>$promo->getId()]);
       if (isset($groupPrincipal)) {
            $formateurs = $groupPrincipal->getFormateurs();
            foreach ($formateurs as $formateur) {
                $tabFormateurs[]= ($this->normalize->normalize($formateur,null,['groups'=>['show_ref_formateur_group']]));
            }
            $tab['formateurs']=$tabFormateurs;
       }
        return $this->json($tab,200,[],['groups'=>["show_ref_formateur_group"]]);
    }
    /**
     * @Route("api/admin/promo/principal", name="admin_promo_principal", methods={"GET"})
     */
    public function admin_promo_principal()
    {
        $promos = $this->repoPromo->findAll();
        $tab = $this->normalize->normalize($promos,null,['groups'=>["show_apprenant_group"]]);
        foreach($promos as $key => $promo)
        {
        $groupPrincipal = $this->repoGroupPromo->findOneBy(['nom'=>'principal','promo'=>$promo->getId()]);
            if (isset($groupPrincipal))
            {
                $formateurs = $groupPrincipal->getFormateurs();
                foreach ($formateurs as $formateur)
                {
                    $tabFormateurs[$key][]= ($this->normalize->normalize($formateur,null,['groups'=>["show_apprenant_group"]]));
                }
                $tab[$key]['formateurs']=$tabFormateurs[$key];
            }
        }
        return $this->json($tab,200,[],['groups'=>["show_apprenant_group"]]);
    }
     /**
     * @Route("api/admin/promo/{id}/principal", name="admin_promo_principal_item", methods={"GET"})
     */
    public function admin_promo_principal_item($id)
    {
        $promo = $this->repoPromo->findOneBy(['id'=>$id]);
        $tab = $this->normalize->normalize($promo,null,['groups'=>["show_apprenant_group"]]);
       
        $groupPrincipal = $this->repoGroupPromo->findOneBy(['type'=>'principal','promo'=>$promo->getId()]);
            if (isset($groupPrincipal))
            {
                $formateurs = $groupPrincipal->getFormateurs();
                foreach ($formateurs as $formateur)
                {
                    $tabFormateurs[]= ($this->normalize->normalize($formateur,null,['groups'=>["show_apprenant_group"]]));
                }
                $tab['formateurs']=$tabFormateurs;
            }
        return $this->json($tab,200,[],['groups'=>["show_apprenant_group"]]);
    }
     /**
     * @Route("api/admin/promo/apprenants/attente", name="apprenants_attente", methods={"GET"})
     */
    public function apprenants_attente()
    {
       $promos = $this->repoPromo->findAll();
       $tabPromos = $this->normalize->normalize($promos,null,['groups'=>["apprenants_attente"]]);
       foreach ($tabPromos as $keyPromo => $promo) {
           $tabApprenants = $promo['apprenants'];
           foreach ($tabApprenants as $keyApp => $apprennant) {
               if ($apprennant['status'] != 'attente') {
                   unset($tabApprenants[$keyApp]);
               }
           }
           $tabPromos[$keyPromo]['apprenants']=$tabApprenants;
       }
       return $this->json($tabPromos,200,[],['groups'=>["apprenants_attente"]]);
    }
     /**
     * @Route("api/admin/promo/{id}/apprenants/attente", name="apprenants_attente_item", methods={"GET"})
     */
    public function apprenants_attente_item($id)
    {
       $promo = $this->repoPromo->findOneBy(['id'=>$id]);
       $tabPromos = $this->normalize->normalize($promo,null,['groups'=>["apprenants_attente"]]);
           $tabApprenants = $tabPromos['apprenants'];
           foreach ($tabApprenants as $keyApp => $apprennant) {
               if ($apprennant['status'] != 'attente') {
                   unset($tabApprenants[$keyApp]);
               }
           }
           $tabPromos['apprenants']=$tabApprenants;
       return $this->json($tabPromos,200,[],['groups'=>["apprenants_attente"]]);
    }

    /**
     * @Route("api/admin/promo/{id}/referentiel", name="referentiel_competence", methods={"GET"})
     */
    public function referentiel_competence($id){
        $promo = $this->repoPromo->findOneBy(['id'=>$id]);
        if(isset($promo)){
            return $this->json($promo,200,[],['groups'=>["referentiel_competence"]]);
        }else{
            return $this->json(['message'=>'failed'],404);
        }
    }
    /**
     * @Route("api/admin/promo/{id}/groupes/{idgroupe}/apprenants", name="groupe_apprenants",methods={"GET"})
     */
    public function  groupe_apprenants($id,$idgroupe){
        $groupe  = $this->repoGroupPromo->findOneBy(['id'=>$idgroupe,'promo'=>$id]);
        if (isset($groupe)) {
            return $this->json($groupe,200,[],['groups'=>['groupe_apprenants']]);
        }
    }

    /**
     * @Route("api/admin/promo/{id}/formateurs", name="formateur_groupe", methods={"GET"})
     * 
     */
    public function formateur_groupe($id){
        $promo = $this->repoPromo->findOneBy(['id'=>$id]);
        $groupPrincipal = $this->repoGroupPromo->findOneBy(['type'=>'principal','promo'=>$promo->getId()]);
        $tab = $this->normalize->normalize($promo,null,['groups'=>['formateur_groupe']]);
        if (isset($groupPrincipal)) {
             $formateurs = $groupPrincipal->getFormateurs();
             foreach ($formateurs as $formateur) {
                 $tabFormateurs[]= ($this->normalize->normalize($formateur,null,['groups'=>['formateur_groupe']]));
             }
             $tab['formateurs']=$tabFormateurs;
             return $this->json($tab,200,[]);

        }else{
            return $this->json(['message'=>'failed'],404);
        }
    }
}
