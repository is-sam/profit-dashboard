<?php

namespace App\Form;

use App\Entity\CustomCost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomCostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Custom expense name ...'
                ]
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('amount', MoneyType::class)
            ->add('frequency', ChoiceType::class, [
                'choices' => [
                    CustomCost::FREQUENCY_ONETIME => 'One time',
                    CustomCost::FREQUENCY_DAILY => 'Daily',
                    CustomCost::FREQUENCY_WEEKLY => 'Weekly',
                    CustomCost::FREQUENCY_MONTHLY => 'Monthly',
                    CustomCost::FREQUENCY_QUARTERLY => 'Quarterly',
                    CustomCost::FREQUENCY_YEARLY => 'Yearly',
                ]
            ])
            ->add('cancel', ButtonType::class, [
                'attr' => [
                    'data-modal-close' => 'add_custom_cost'
                ]
            ])
            ->add('add', SubmitType::class, [
                'attr' => [
                    'class' => 'px-4 py-2 text-white font-semibold bg-blue-500 rounded'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomCost::class,
        ]);
    }
}
