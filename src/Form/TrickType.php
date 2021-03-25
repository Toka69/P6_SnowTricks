<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Repository\PhotoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

/**
 * Class TrickType
 * @package App\Form
 */
class TrickType extends AbstractType
{
    private PhotoRepository $photoRepository;

    public function __construct(PhotoRepository $photoRepository){
        $this->photoRepository = $photoRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photos', CollectionType::class, [
                'entry_type' => PhotoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                'placeholder' => 'Select a category',
                'class' => Category::class,
                'choice_label' => fn(Category $category) => u($category->getName())->upper()
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $trick = $event->getData();
            if(!empty(!$this->photoRepository->findBy(['trick' => $trick->getId(), 'cover' => true]))) {
                $form->add('fileCover', FileType::class, [
                    'label' => '<i class="fas fa-pencil-alt"></i>',
                    'label_html' => true,
                    'required' => false
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

