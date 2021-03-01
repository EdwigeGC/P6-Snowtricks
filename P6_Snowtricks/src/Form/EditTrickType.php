<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTrickType extends AbstractType
{
    public function setConfigurationAttribute($label)
    {
        return[
            'label'=>$label,
            'required'=> true
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->setConfigurationAttribute("What's its name?", true))
            ->add('description', TextareaType::class, $this->setConfigurationAttribute("Write a description", true))
            ->add('category', EntityType::class, [
                'label' => "Choose a category",
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'multiple' => false,
                'expanded' => false,
                'required'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
