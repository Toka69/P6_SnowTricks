<?php


namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user->isVerified()){
            throw new CustomUserMessageAccountStatusException("Your account is not yet been verified!");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}