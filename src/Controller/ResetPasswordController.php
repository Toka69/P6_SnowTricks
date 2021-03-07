<?php


namespace App\Controller;


use App\Form\ResetPasswordType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset", name="reset_password")
     * @param Request $request
     * @return Response
     */
    public function resetPassword(Request $request, MailerInterface $mailer){
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = new TemplatedEmail();
            $email->from(new Address('dev.tokashi@gail.com', 'SnowTricks'))
                ->to($form->getData()['email'])
                ->subject('Reset your Password')
                ->text('click here')
            ;
            $mailer->send($email);

            $this->addFlash('success', 'Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/resetPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
