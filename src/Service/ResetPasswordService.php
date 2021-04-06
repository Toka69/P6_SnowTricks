<?php


namespace App\Service;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordService
{
    private UserRepository $userRepository;

    private TokenGeneratorInterface $tokenGenerator;

    private EntityManagerInterface $em;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function persistTokenResetPassword($form, $token)
    {
        $user = $this->userRepository->findOneBy(['email' => $form->getData()['email']]);
        $user->setToken($token);
        $this->em->flush();
    }

    public function persistNewPassword($user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setToken('');
        $this->em->persist($user);
        $this->em->flush();
    }
}
