<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Partenaire;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('profil',EntityType::class,[
                'class1'=> Profil::class,
                'choice_label1'=> 'profil_id'
                ])
            ->add('adresse')
            ->add('email')
            ->add('telephone')
            ->add('adresse')
            ->add('cni')
            ->add('compte',EntityType::class,[
                'class'=> Compte::class,
                'choice_label'=> 'compte_id'
                ])
            ->add('statut')
            ->add('imageFile',VichImageType::class)
            ->add('partenaire',EntityType::class,[

                'class'=> Partenaire::class,
                'choice_label'=>'partenaire_id'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection'=>false
        ]);
    }
}
