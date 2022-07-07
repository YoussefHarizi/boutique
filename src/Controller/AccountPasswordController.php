<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    
    
    #[Route('/compte/password', name: 'app_account_password')]
    public function index(Request $request,UserPasswordHasherInterface  $encoder): Response
    {
        $notification=null;
        $user=$this->getUser();
        $form=$this->createForm(ChangePasswordType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $old_pwd=$form->get('old_password')->getData();
            if($encoder->isPasswordValid($user,$old_pwd)){
                $new_pwd=$form->get('new_password')->getData();
                $password=$encoder->hashPassword($user,$new_pwd);
                $user->setPassword($password);
                $this->entityManager->flush();
                $notification='Votre mot de passe à bien été mis à jour';
                
            }else{
                $notification='Vérifier votre mot de passe';

            }
           
        }



        return $this->render('account/renewPassword.html.twig', [
            'form'=>$form->createView(),
            'notification'=>$notification
        ]);
    }
}
