<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',

            ])
            ->add('subject', TextareaType::class, [
                'empty_data' => '',
            ])

            ->add('message', TextareaType::class, [
                'empty_data' => '',
            ])

            ->add('service', ChoiceType::class, [
                'choices'  => [
                    'compta' => 'compta@demo.fr',
                    'meca' => 'meca@demo.fr',
                    'autre' => 'autre@demo.fr',
                ],
            ])

            ->add('save',SubmitType::class,[
                    'label' => 'Envoyer',
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
