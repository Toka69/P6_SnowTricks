<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class PhotoType
 * @package App\Form
 */
class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            $photo = $event->getData();

            if ($photo) {
                $form->add('file', FileType::class, [
                    'label' => '<i class="fas fa-pencil-alt"></i>',
                    'label_html' => true,
                    'attr' => ['accept' => 'image/jpeg, image/png'],
                    'constraints' => [new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ]
                        ])
                    ],
                    'required' => false
                ])
                    ->add('delete', ButtonType::class, [
                    'attr' => ['class' => 'delete'],
                    'label' => '<i class="fas fa-trash-alt"></i>',
                    'label_html' => true
                ]);
            }
            else{
                $form->add('file', FileType::class, [
                    'label' => 'Add a photo',
                    'label_attr' => ['class' => 'btn btn-primary'],
                    'attr' => ['accept' => 'image/jpeg, image/png'],
                    'constraints' => [new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ]
                    ])
                    ],
                    'required' => false
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}

