<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$options['user'];
        $builder
            ->add('addresses',EntityType::class,[
                'label'=>'Choisissez votre adresse de livraison',
                'required'=>true,
                'class'=>Adresse::class,
                'choices'=>$user->getAdresses(),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('carriers',EntityType::class,[
                'label'=>'Choisissez Le transporteur',
                'required'=>true,
                'class'=>Carrier::class,
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('submit',SubmitType::class,[
                'label'=>"Valide ma commande",
                'attr'=>[
                    'class'=>'btn  btn-lg btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user'=>array()
        ]);
    }
}
