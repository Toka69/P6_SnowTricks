<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function checkPreAuth(UserInterface $user)
    {
        if($this->request->attributes->get('_route') != "login_check" && !$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException("Your account is not yet been verified!");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}