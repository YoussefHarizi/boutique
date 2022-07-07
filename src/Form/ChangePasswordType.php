<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled'=>true,
                'label'=>'Adresse Email'
            ])
            ->add('firstname',TextType::class,[
                'disabled'=>true,
                'label'=>'Prénom'
            ])
            ->add('lastname',TextType::class,[
                'disabled'=>true,
                'label'=>'Nom'
            ])
            ->add('old_password',PasswordType::class,[
                'mapped'=>false,
                'label'=>'Le mot de passe actuel',
                'attr'=>[
                    'placeholder'=>'veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password',RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation du mot depasse doivent être identique !',
                
                'required'=>true,
                'mapped'=>false,
                'first_options'=>[
                    'label'=>'Votre nouveau mot de passe',
                    'attr'=>[
                        'placeholder'=>'nouveau mot de passe'
                    ]
                    ],
                'second_options'=>[
                    'label'=>'Confirmer le nouveau mot du passe',
                    'attr'=>[
                        'placeholder'=>'retaper le nouveau mot de passe'
                    ]
                    ],
            ])
            ->add('submit',SubmitType::class,[
                'label'=>"mettre à jour",
            
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
