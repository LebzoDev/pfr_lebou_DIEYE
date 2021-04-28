<?php

namespace App\EventListener;

use App\Repository\ApprenantRepository;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener{
    private $repo;
    private $repo_app;
    public function __construct(RequestStack $requestack ,UserRepository $repo,ApprenantRepository $repo_app)
    {
        $this->repo = $repo;
        $this->repo_app = $repo_app;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
       
        $payload       = $event->getData();
        //dd($payload, $this->repo->findOneBy(['username'=>$payload['username']]));
        $user = $this->repo->findOneBy(['username'=>$payload['username']]);
        if ($payload['roles'][0]=='ROLE_APPRENANT') {
            $user = $this->repo_app->findOneBy(['username'=>$payload['username']]);
            $payload['status']=$user->getStatus();
            $payload['id']=$user->getId();
        }
        
        $payload['archive'] = $user->getArchive();

        $event->setData($payload);
        
        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}