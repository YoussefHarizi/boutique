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

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname',TextType::class,[
            'label'=>'Votre Prénom',
            'attr'=>[
                'placeholder'=>'Prénom'
            ]
        ])
        ->add('lastname',TextType::class,[
            'label'=>'Votre nom',
            'attr'=>[
                'placeholder'=>'Nom'
            ]
            ])
        ->add('email',EmailType::class,[
            'label'=>'Votre Email',
            'attr'=>[
                'placeholder'=>'Email'
            ]
            ])
        ->add('password',RepeatedType::class,[
            'type'=>PasswordType::class,
            'invalid_message'=>'Le mot de passe et la confirmation du mot depasse doivent être identique !',
            
            'required'=>true,
            'first_options'=>[
                'label'=>'Votre mot de passe',
                'attr'=>[
                    'placeholder'=>'mot de passe'
                ]
                ],
            'second_options'=>[
                'label'=>'Confirmation de mot du passe',
                'attr'=>[
                    'placeholder'=>'retaper le mot de passe'
                ]
                ],
        ])
        ->add('submit',SubmitType::class,[
            'label'=>"S'inscrire",
        
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
