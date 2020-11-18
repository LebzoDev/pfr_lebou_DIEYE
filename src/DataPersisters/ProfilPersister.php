<?php

namespace App\DataPersisters;


use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;


class ProfilPersister implements DataPersisterInterface{

    private $manager;
    private $request;
    private $decorated;
    public function __construct(EntityManagerInterface $manager, RequestStack $request){
        $this->manager = $manager;
        $this->request = $request->getCurrentRequest();
    }

    public function supports($data): bool{
        return $data instanceof Profil;
    }

    public function persist($data, array $context=[])
    {
        if (isset($context['item_operation_name']) && $context['item_operation_name']=='put'){
            $this->manager->persist($data);
            $this->manager->flush();
        }

        if (isset($context['collection_operation_name']) && $context['collection_operation_name']=='post'){
            $data->setArchive(false);
            $this->manager->persist($data);
            $this->manager->flush();
        }
    } 
    
    public function remove($data){

        $this->manager->remove($data);
        $this->manager->flush();
    }
    
}