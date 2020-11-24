<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apprenant;
use App\Controller\UserController;
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

class UserController extends AbstractController
{
    private $encoder;
    private $manager;
    private $repoUser; 
    private $repoprofil;
    public function __construct(EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, ProfilRepository $repoprofil,UserRepository $repoUser)
    {
        $this->encoder=$encoder;
        $this->manager = $manager;
        $this->repoprofil = $repoprofil;
        $this->repoUser = $repoUser;

    }
    /**
     * @Route(
     *     name="addUser",
     *     path="/api/users",
     *     methods={"POST"},
     *     defaults={
     *          "controller"="App\Controller\UserController::addUser",
     *          "__api_resource_class"=User::class,
     *     }
     * )
     */
    public function addUser(Request $request)
    {
        $array = $request->request->all();
        $user = new User();
        $photo = $request->files->get("photo");
        $photo = fopen($photo->getRealPath(),"rb");
        //$photo = base64_encode($photo);
        if (isset($photo)) {
            $user->setPhoto($photo);
        }
        $user->setPrenom($array['prenom']);
        $user->setNom($array['nom']);
        $user->setUsername($array['username']);
        $user->setPassword($array['password']);
        $profil = $this->repoprofil->findOneBy([]);

        $user->setProfil($profil);
        $user->setPhoto($photo);
        
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));

        $this->manager->persist($user);
        $this->manager->flush();
        fclose($photo);
       
        dd($user);
        return $this->json("success",201);
    }




    /**
     * @Route(
     *     name="putUser",
     *     path="/api/users/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "controller"="App\Controller\UserController::putUser",
     *          "__api_resource_class"=User::class,
     *     }
     * )
     */
    public function putUser(Request $request,$id)
    {
        $userDonne = $this->repoUser->findOneBy(['id'=>$id]);
        $data = $request->getContent();
        $photo = $request->files->get("photo");
        dd($request->getPathInfo());
        //$photo = fopen($photo->getRealPath(),"rb");
        //$photo = base64_encode($photo);
       
        if (isset($photo)) {
            $userDonne->setPhoto($photo);
        }
        /*$user = new User();
        $user->setPrenom($array['prenom']);
        $user->setNom($array['nom']);
        $user->setUsername($array['username']);
        $user->setPassword($array['password']);
        $profil = $this->repoprofil->findOneBy([]);

        $user->setProfil($profil);
        $user->setPhoto($photo);
        
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        */
        //$this->manager->persist($userDonne);
        //$this->manager->flush();
        fclose($photo);
       
        
        return $this->json($userDonne,201);
    }
}
