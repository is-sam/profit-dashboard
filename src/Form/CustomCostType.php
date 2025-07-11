<?php

namespace App\Form;

use App\Entity\CustomCost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                    'placeholder' => 'Custom expense name ...',
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('amount', MoneyType::class)
            ->add('frequency', ChoiceType::class, [
                'choices' => [
                    'One time' => CustomCost::FREQUENCY_ONETIME,
                    'Daily' => CustomCost::FREQUENCY_DAILY,
                    'Weekly' => CustomCost::FREQUENCY_WEEKLY,
                    'Monthly' => CustomCost::FREQUENCY_MONTHLY,
                    'Quarterly' => CustomCost::FREQUENCY_QUARTERLY,
                    'Yearly' => CustomCost::FREQUENCY_YEARLY,
                ],
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
