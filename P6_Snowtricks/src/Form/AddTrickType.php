<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Category;
use App\Form\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddTrickType extends AbstractType
{
    /**
     * Configures label input
     *
     * @param string $label
     * @return array
     */
    public function setConfigurationAttribute($label){
        return[
            'label'=>$label,
            ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->setConfigurationAttribute("What is your trick's name?"))
            ->add('category', EntityType::class, [
                'label' => "Choose a category", 
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('description', TextareaType::class, $this->setConfigurationAttribute("Write a description in maximum details."))
            ->add('pictures', CollectionType::class, [
                    'entry_type' => PictureType::class,
                        'allow_add' => true,
                        'allow_delete'=> true
                
            ])
            ->add('videos')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
