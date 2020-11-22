<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apprenant;
use App\Controller\UserController;
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
    public function __construct(EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder, ProfilRepository $repoprofil)
    {
        $this->encoder=$encoder;
        $this->manager = $manager;
        $this->repoprofil = $repoprofil;
    }
    /**
     * @Route(
     *     name="addUsert",
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

        $photo = $request->files->get("photo");

        $user = new User();
        $user->setPrenom($array['prenom']);
        $user->setNom($array['nom']);
        $user->setUsername($array['username']);
        $user->setPassword($array['password']);
        $profil = $this->repoprofil->findOneBy([]);

        $photo = fopen($photo->getRealPath(),"rb");
        $user->setProfil($profil);
        $user->setPhoto($photo);
        
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));

        $this->manager->persist($user);
        $this->manager->flush();
        fclose($photo);
       
        
        return $this->json("success",201);
    }
}
