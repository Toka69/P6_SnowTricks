<?php


namespace App\Controller;


use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset", name="reset_password")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function resetPassword(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository,
    EntityManagerInterface $em){
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $token = $tokenGenerator->generateToken();
            if ($userRepository->findOneBy(['email' => $form->getData()['email']])) {
                $user = $userRepository->findOneBy(['email' => $form->getData()['email']]);
                $user->setToken($token);
                $em->flush();

                $url = $this->generateUrl('new_password', array('email' => $form->getData()['email'], 'token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                $email = new TemplatedEmail();
                $email->from(new Address('dev.tokashi@gail.com', 'SnowTricks'))
                    ->to($form->getData()['email'])
                    ->subject('Reset your Password')
                    ->text('click here '.$url.'');
                $mailer->send($email);
            }
            $this->addFlash('success', 'Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/resetPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new-password", name="new_password")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function newPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em){
        if(!is_null($request->get('email') && $request->get('token')))
        {
            $user = $userRepository->findOneBy(['email' => $request->get('email')]);

            if (is_null($user)){
                return $this->redirectToRoute('security_login');
            }

            if ($user->getToken() === $request->get('token'))
            {
                $form = $this->createForm(UserType::class, $user);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                    $user->setToken('');
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Your password has been changed!');

                    return $this->redirectToRoute('security_login');
                }

                return $this->render('security/newPassword.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        $this->addFlash('danger', 'The link doesnt works');
        
        return $this->redirectToRoute('homepage');
    }
}
