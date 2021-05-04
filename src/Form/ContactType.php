<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['placeholder' => 'John']
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['placeholder' => 'Cena']
            ])
            ->add('email', TextType::class, [
                'attr' => ['placeholder' => 'john_cena@mail.com']
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['placeholder' => 'Is behind you...']
            ])
            ->add('attachment', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Attach your CV'
            ])
            //->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
