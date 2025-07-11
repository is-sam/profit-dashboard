<?php

namespace App\Form\Type;

use App\Form\Model\DashboardSearch;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateType::class, [
                'label' => 'dashboard.filter.datestart',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'px-4 py-3 mt-2 rounded-md bg-gray-800 border-0',
                ],
            ])
            ->add('dateEnd', DateType::class, [
                'label' => 'dashboard.filter.dateend',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'px-4 py-3 mt-2 rounded-md bg-gray-800 border-0',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DashboardSearch::class,
        ]);
    }
}
