<?php

namespace App\DataPersisters;


use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;


class ProfilPersister implements DataPersisterInterface{

    private $manager;
    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function supports($data): bool{
        return $data instanceof Profil;
    }

    public function persist($data)
    {
        if (($context[’collection_operation_name’] ?? null) === ’post’) {
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