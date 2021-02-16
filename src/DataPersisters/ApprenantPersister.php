<?php

namespace App\DataPersisters;

use App\Entity\Apprenant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class ApprenantPersister implements DataPersisterInterface{

    private $manager;
    private $request;
    private $decorated;
    private $normalized;

 public function __construct(EntityManagerInterface $manager, RequestStack $request){
        $this->manager = $manager;
        $this->request = $request->getCurrentRequest();
    }

    public function supports($data): bool{
         //dd($data);
        return $data instanceof Apprenant;
    }

    public function persist($data, array $context=[])
    {
    $array = json_encode($data);
      if (isset($context['collection_operation_name']) && $context['collection_operation_name']=='post'){
             $data->setArchive(false);
            $this->manager->persist($data);
             $this->manager->flush();
        }else{
            //$data->setStatus('active');
            $this->manager->persist($data);
            $this->manager->flush();
        }
    } 
    
    public function remove($data){
        $data->setArchive(true);
        $this->persist($data);
        $this->manager->flush();
    }
    

}