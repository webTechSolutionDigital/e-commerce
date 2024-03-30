<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\formListernerFactory;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function __construct(private formListernerFactory $listenerFactory)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            // ->add('recipes', EntityType::class, [
            //     'class' => Recipe::class,
            //     'choice_label' => 'title',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'by_reference' => false,
            // ])
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Sauvegarder',
                ]
            )


            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->timeStamps());
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
