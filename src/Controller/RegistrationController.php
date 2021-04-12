<?php


namespace App\Controller;


use App\Form\ResendActivationLink;
use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\Manager\RegistrationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    protected RegistrationService $registrationService;

    protected Mailer $mailer;

    public function __construct(RegistrationService $registrationService, Mailer $mailer){
        $this->registrationService = $registrationService;
        $this->mailer = $mailer;
    }

    /**
     * @param Request $request
     * @Route("register", name="register", priority=1)
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            try {
                $this->registrationService->persist($user);
                $loginLink = $this->registrationService->createLoginLink($user);
                $this->mailer->registrationSendEmailSuccess($user, $loginLink);

                $this->addFlash('success', 'Your account has been created! Check your emails to valid it');
            } catch (Exception $e) {
                $this->addFlash('error', 'An error occurred. Please retry and contact support if need help.');
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     * @Route("resend", name="resend_activation_link")
     */
    public function resendActivationLink(Request $request, UserRepository $userRepository)
    {
        $form = $this->createForm(ResendActivationLink::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $userRepository->findOneBy(['email' => $form->getData()['email']]);
                if ($user) {
                    $loginLink = $this->registrationService->createLoginLink($user);
                    $this->mailer->registrationSendEmailSuccess($user, $loginLink);

                    $this->addFlash('success', 'Check your emails to valid it');
                }
            } catch (Exception $e) {
                $this->addFlash('error', 'An error occurred. Please retry and contact support if need help.');
            }
        }
        return $this->render('security/resendActivationLink.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}
