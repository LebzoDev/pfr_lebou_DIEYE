<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Service\AddUtilisateur;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $encoder;
    private $manager;
    private $repoUser; 
    private $repoprofil;
    private $security;
    private $repo_apprenant;
    public function __construct(Security $security,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, ProfilRepository $repoprofil,UserRepository $repoUser,ApprenantRepository $repo_apprenant)
    {
        $this->encoder=$encoder;
        $this->manager = $manager;
        $this->repoprofil = $repoprofil;
        $this->repoUser = $repoUser;
        $this->security = $security;
        $this->repo_apprenant = $repo_apprenant;
    }
    /**
     * @Route(
     *     name="addUser",
     *     methods={"POST"},
     *     defaults={
     *          "path"="api/admin/users",
     *          "__api_resource_class"=User::class,
     *     }
     * )
     */
    public function addUser(Request $request)
    {
        $array = $request->request->all();
        //$data = json_decode($request->getContent(), true);
        $user = new User();
        $photo = $request->files->get("photo");
        //$photo = base64_encode($photo);
        if (isset($photo)) {
            $photo = fopen($photo->getRealPath(),"rb");
            $user->setPhoto($photo);
        }
        //dd($array);
        $user->setPrenom($array['prenom']);
        $user->setNom($array['nom']);
        $user->setUsername($array['username']);
        $user->setPassword($array['password']);
        $user->setEmail($array['email']);
        $profil = $this->repoprofil->findOneBy(['id'=>$array['id']]);
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        if(isset($profil)){
            $user->setProfil($profil);
        }else{
            return $this->json("echec",400);
        }
        $this->manager->persist($user);
        $this->manager->flush();
        if (isset($photo)) {
            fclose($photo);
        }
        return $this->json("success",201);
    }


     /**
     * @Route(
     *     name="addAPprenant",
     *     methods={"POST"},
     *     defaults={
     *          "path"="api/apprenants",
     *          "__api_resource_class"=Aprenant::class,
     *     }
     * )
     */
    public function addApprenant(Request $request,\Swift_Mailer $mailer)
    {
        $array = $request->request->all();
        $currentUser=$this->security->getUser();
        //$currentMail=$currentUser->getEmail();
        //dd($currentMail);
        //$data = json_decode($request->getContent(), true);
        $user = new User();
        $photo = $request->files->get("photo");
        //$photo = base64_encode($photo);
        if (isset($photo)) {
            $photo = fopen($photo->getRealPath(),"rb");
            $user->setPhoto($photo);
        }
        //dd($array);
        $user->setPrenom($array['prenom']);
        $user->setNom($array['nom']);
        $user->setUsername($array['username']);
        $user->setPassword($array['password']);
        $user->setEmail($array['email']);
        $profil = $this->repoprofil->findOneBy(['id'=>$array['idProfil']]);
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        if(isset($profil)){
            $user->setProfil($profil);
        }else{
            return $this->json("echec",400);
        }
        $this->manager->persist($user);
        //$this->manager->flush();
        if (isset($photo)){
            fclose($photo);
        }
        //$mail = $this->security->getUser()->getEmail();
        $message = (new \Swift_Message('Hello Email'))  
            ->setFrom('luckylucky96123@gmail.com')
            ->setTo('leboundiayee123@gmail.com')
            ->setBody("Bonjour cher(e) apprenant(e), vous avez été selection à la Formation SONATEL ACADEMY
                .Veuillez confirmer afin de nous rejoindre.");
        $mailer->send($message);
        return $this->json("success",201);
    }


    /**
     * @Route(
     *     name="putUser",
     *     path="api/admin/users/{id}",
     *     methods={"put"},
     *     defaults={
     *          "__api_resource_class"=User::class,
     *     }
     * )
     */
    public function putUser(Request $request,$id,AddUtilisateur $getFields)
    {
        
        $userDonne = $this->repoUser->findOneBy(['id'=>$id]);

           $data = $request->getContent();
        // $array = $request->request->all();
        // $photo = $request->files->get("photo");
        // if (isset($photo)) {
        //     $photo = fopen($photo->getRealPath(),"rb");
        //     $userDonne->setPhoto($photo);
        // }

        //Définir le tableau qui contiendra les données recupérées
            $dataGot = [];
        //Appliquer la fonction de notre service
            $dataGot = $getFields->transformData($data);

            //dd($userDonne,$dataGot);
            
            // $userDonne->setPrenom($array['prenom']);
            // $userDonne->setNom($array['nom']);
            // $userDonne->setUsername($array['username']);
            // $userDonne->setPassword($array['password']);
            // $userDonne->setEmail($array['email']);
            // $profil = $this->repoprofil->findOneBy(['id'=>$array['id']]);
            // $password = $userDonne->getPassword();
            // $userDonne->setPassword($this->encoder->encodePassword($userDonne,$password));
            // if(isset($profil)){
            //     $userDonne->setProfil($profil);
            // }else{
            //     return $this->json("echec",400);
            // }

        // dd($dataGot);

            if (isset($dataGot['photo'])) {
                $photo = fopen('php://memory','r+');
                fwrite($photo, $dataGot['photo']);
                rewind($photo);
            }
            foreach ($dataGot as $key => $value) {
                $methode = 'set'.ucfirst($key);
                if(method_exists($userDonne,$methode)){
                    $userDonne->$methode($value);
                }
            }
       
        $this->manager->persist($userDonne);
        $this->manager->flush();

        return $this->json(['message'=>'success'],201);
    }

      /**
     * @Route(
     *     name="putUser",
     *     path="api/apprenants_active/{id}",
     *     methods={"put"},
     *     defaults={
     *          "__api_resource_class"=User::class,
     *     }
     * )
     */
    public function activeApprenant(Request $request,$id,AddUtilisateur $getFields)
    {
        
        $userDonne = $this->repo_apprenant->findOneBy(['id'=>$id]);

           $data = $request->getContent();
        // $array = $request->request->all();
        // $photo = $request->files->get("photo");
        // if (isset($photo)) {
        //     $photo = fopen($photo->getRealPath(),"rb");
        //     $userDonne->setPhoto($photo);
        // }

        //Définir le tableau qui contiendra les données recupérées
            $dataGot = [];
        //Appliquer la fonction de notre service
            $dataGot = $getFields->transformData($data);

            //dd($userDonne,$dataGot);
            
            // $userDonne->setPrenom($array['prenom']);
            // $userDonne->setNom($array['nom']);
            // $userDonne->setUsername($array['username']);
            // $userDonne->setPassword($array['password']);
            // $userDonne->setEmail($array['email']);
            // $profil = $this->repoprofil->findOneBy(['id'=>$array['id']]);
            // $password = $userDonne->getPassword();
            // $userDonne->setPassword($this->encoder->encodePassword($userDonne,$password));
            // if(isset($profil)){
            //     $userDonne->setProfil($profil);
            // }else{
            //     return $this->json("echec",400);
            // }

        // dd($dataGot);

            if (isset($dataGot['photo'])) {
                $photo = fopen('php://memory','r+');
                fwrite($photo, $dataGot['photo']);
                rewind($photo);
            }
            foreach ($dataGot as $key => $value) {
                $methode = 'set'.ucfirst($key);
                if(method_exists($userDonne,$methode)){
                    $userDonne->$methode($value);
                }
            }
        $userDonne->setStatus('active');
        $this->manager->persist($userDonne);
        $this->manager->flush();

        return $this->json(['message'=>'success active apprenant','donnees'=>$dataGot],200);
    }
}
