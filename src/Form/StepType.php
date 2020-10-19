<?php

namespace App\Form;

use App\Entity\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Numéro de l\'étape',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'étape',
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de l\'étape',
                'required' => false
            ])
            //->add('video_url')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
