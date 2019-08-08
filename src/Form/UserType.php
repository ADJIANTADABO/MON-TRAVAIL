<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\CompteType;
use App\Form\ProfilType;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
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
            ->add('imageFile',VichImageType::class)
            
            ->add('compte',EntityType::class,[
                'class'=> Compte::class,
                'choice_label'=> 'compte_id',
            ])
            // ->add('partenaire',EntityType::class,[
            //     'class'=> Partenaire::class,
            // //     'choice_label'=> 'partenaire_id',
            // ])
            ->add('profil',EntityType::class,[
                'class'=> Profil::class
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
