<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener{
    private $repo;

    public function __construct(RequestStack $requestack ,UserRepository $repo)
    {
        $this->repo = $repo;
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
        $payload['archive'] = $user->getArchive();

        $event->setData($payload);
        
        $header        = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}