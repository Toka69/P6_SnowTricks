<?php


namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    protected $em;

    protected $flashbag;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flashBag){
        $this->em = $em;
        $this->flashbag = $flashBag;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event){
        if($event->getRequest()->attributes->get('_route') == "login_check") {
            $user = $event->getAuthenticationToken()->getUser();
            $user->setIsVerified(true);
            $this->em->flush();

            $this->flashbag->add('success', 'Your email has been verified and you are logged in');
        }
    }
}