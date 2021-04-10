<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $newPassword = $options['newPassword'];
        $builder->add('Save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($newPassword) {
            $form = $event->getForm();

            if ($this->security->getUser() === null && $newPassword === false)
            {
                $form->add('firstName', TextType::class)
                    ->add('lastName', TextType::class)
                    ->add('email', EmailType::class, [
                        'constraints' => new Email()
                    ])
                    ->add('plainPassword', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password'),
                        'invalid_message' => 'Passwords must be the same!'
                    ));
            }
            elseif ($newPassword === true)
            {
                $form->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password')
                ));
            }
            else
            {
                $form->add('file', FileType::class, [
                    'label' => 'Add or Edit a photo',
                    'label_attr' => ['class' => 'btn btn-primary'],
                    'attr' => ['accept' => 'image/*'],
                    'required' => false
                ])
                    ->add('firstName', TextType::class)
                    ->add('lastName', TextType::class)
                    ->add('email', EmailType::class, [
                        'disabled' => true
                    ])
                    ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'newPassword' => false
        ]);
    }
}
