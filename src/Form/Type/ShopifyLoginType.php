<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopifyLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shop', TextType::class, [
                'label' => 'auth.login.shop_url',
                'attr' => [
                    'class' => 'block w-full my-2 py-2 border-2 rounded-md bg-gray-800',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'auth.login.submit',
                'attr' => [
                    'class' => 'block w-full mt-10 my-2 py-4 border-2 rounded-md bg-blue-800',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
