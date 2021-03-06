<?php


namespace App\Controller;


use App\Form\UserType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoginLinkHandlerInterface $loginLinkHandler
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     * @Route("register", name="register", priority=1)
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginLinkHandlerInterface $loginLinkHandler, EntityManagerInterface $em, MailerInterface $mailer){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);
            $loginLink = $loginLinkDetails->getUrl();

            $email = new TemplatedEmail();
            $email->from(new Address('dev.tokashi@gail.com', 'Snowtricks'))
                ->to($user->getEmail())
                ->text('Click on the link below to valid your account :')
                ->subject('Valid your account')
                ->htmlTemplate('emails/signup.html.twig')
                ->context([
                    'link' => $loginLink,
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => $user->getFirstName()
                ])
            ;
            $mailer->send($email);

            $this->addFlash('success', 'Your account has been created! Check your emails to valid it');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
            ]
        );
    }
}
