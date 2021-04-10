<?php


namespace App\Controller;


use App\Form\UserType;
use App\Entity\User;
use App\Service\Mailer;
use App\Service\Manager\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return RedirectResponse|Response
     * @Route("register", name="register", priority=1)
     */
    public function register(Request $request): RedirectResponse|Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->registrationService->persist($user);
            $loginLink = $this->registrationService->createLoginLink($user);
            $this->mailer->registrationSendEmailSuccess($user, $loginLink);

            $this->addFlash('success', 'Your account has been created! Check your emails to valid it');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
            ]
        );
    }
}
