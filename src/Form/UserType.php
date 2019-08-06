<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email')
            ->add('telephone')
            ->add('cni')
            ->add('compte',EntityType::class,[
                'class1'=> Compte::class,
                'choice_label1'=> 'compte_id'
            ])
            ->add('partenaire',EntityType::class,[
                'class'=> Partenaire::class,
                'choice_label'=> 'partenaire_id'
            ])
            ->add('profil',EntityType::class,[
                'class'=> Profil::class,
                'choice_label'=> 'profil_id'
            ])
            ->add('statut');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
