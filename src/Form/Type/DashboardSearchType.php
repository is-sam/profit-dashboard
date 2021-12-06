<?php

namespace App\Form\Type;

use App\Form\Model\DashboardSearch;
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
            ->add('dateStart', DateType::class, ['label' => 'dashboard.filter.datestart'])
            ->add('dateEnd', DateType::class, ['label' => 'dashboard.filter.dateend'])
            ->add('submit', SubmitType::class, ['label' => 'dashboard.filter.submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DashboardSearch::class,
        ]);
    }
}
