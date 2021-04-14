<?php


namespace App\Controller;


use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\Manager\ResetPasswordService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    protected ResetPasswordService $resetPasswordService;

    protected Mailer $mailer;

    public function __construct(ResetPasswordService $resetPasswordService, Mailer $mailer){
        $this->resetPasswordService = $resetPasswordService;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/reset", name="reset_password")
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UserRepository $userRepository
     * @return Response
     */
    public function resetPassword(Request $request, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try {
                if ($userRepository->findOneBy(['email' => $form->getData()['email']])) {
                    $token = $tokenGenerator->generateToken();
                    $url = $this->generateUrl('new_password', array('email' => $form->getData()['email'], 'token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                    $this->resetPasswordService->persistTokenResetPassword($form, $token);
                    $this->mailer->resetPasswordSendEmailSuccess($form, $url);
                }
                $this->addFlash('success', "Check your email for a link to reset your password. If it doesn't appear within a few minutes, check your spam folder.");
            } catch (Exception $e) {
                $this->addFlash('error', 'An error occurred. Please retry and contact support if need help.');
            }
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
     * @return Response
     */
    public function newPassword(Request $request, UserRepository $userRepository): Response
    {
        if(($request->get('email') && $request->get('token')) !== null)
        {
            $user = $userRepository->findOneBy(['email' => $request->get('email')]);

            if ($user === null){
                return $this->redirectToRoute('security_login');
            }

            if ($user->getToken() === $request->get('token'))
            {
                $form = $this->createForm(UserType::class, $user, ['newPassword' => true]);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {
                    $this->resetPasswordService->persistNewPassword($user);

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
