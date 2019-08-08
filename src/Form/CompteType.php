<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use App\Entity\Partenaire;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numcompte')
            ->add('solde')
            ->add('Partenaire',EntityType::class,[
                'class'=> Partenaire::class,
                'choice_label'=> 'partenaire_id'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
