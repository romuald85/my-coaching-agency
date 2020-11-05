<?php

namespace App\Form;

use App\Entity\Bill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('adress2', TextType::class, [
                'label' => 'Adresse 2 (optionnel)',
                'required' => false
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('postalCode', IntegerType::class, [
                'label' => 'Code postal',
            ])
            
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-block btn-primary'],
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
        ]);
    }
}
