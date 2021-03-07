<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    protected $security;

    protected $request;

    public function __construct(Security $security, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->request = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Save', SubmitType::class);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            if ((is_null($this->security->getUser())) && $this->request->getCurrentRequest()->getPathInfo() !== "/new-password")
            {
                $form->add('firstName', TextType::class)
                    ->add('lastName', TextType::class)
                    ->add('email', EmailType::class)
                    ->add('plainPassword', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'first_options' => array('label' => 'Password'),
                        'second_options' => array('label' => 'Repeat Password')
                    ));
            }
            elseif ($this->request->getCurrentRequest()->getPathInfo() == "/new-password")
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
                    'label' => 'Photo',
                    'required' => false
                ])
                    ->add('firstName', TextType::class)
                    ->add('lastName', TextType::class)
                    ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
