<?php


namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }


    public function registrationSendEmailSuccess($user, $loginLink)
    {
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
        $this->mailer->send($email);
    }
}