<?php


namespace App\Service\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class RegistrationService
{
    private UserPasswordEncoderInterface $passwordEncoder;

    private EntityManagerInterface $em;

    private LoginLinkHandlerInterface $loginLinkHandler;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, LoginLinkHandlerInterface $loginLinkHandler){
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->loginLinkHandler = $loginLinkHandler;
    }

    public function persist(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function createLoginLink(User $user)
    {
        $loginLinkDetails = $this->loginLinkHandler->createLoginLink($user);

        return $loginLinkDetails->getUrl();
    }
}