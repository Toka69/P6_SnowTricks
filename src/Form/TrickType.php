<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photos', CollectionType::class, [
                'entry_type' => PhotoType::class,
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
            ->add('fileCover', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
