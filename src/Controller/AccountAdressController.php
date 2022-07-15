<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    
    #[Route('/compte/adress', name: 'app_account_adress')]
    public function index(): Response
    {
        return $this->render('account/adress.html.twig', [
            
        ]);
    }

    #[Route('/compte/newadress', name: 'app_account_add_adress')]
    public function create(Request $request): Response
    {
        $adress = new Adresse();

        $form = $this->createForm(AdressType::class, $adress);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adress->setUser($this->getUser());
            $this->entityManager->persist($adress);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_adress');
        }
        return $this->render('account/formAdress.html.twig', [
            "form"=> $form->createView(),
        ]);
    }

    #[Route('/compte/update/adresse/{id}', name: 'app_account_update_adress')]
    public function update(Request $request,$id): Response
    {
        $address = $this->entityManager->getRepository(Adresse::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_adress');
        }

        $form = $this->createForm(AdressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_adress');
        }

        return $this->render('account/formAdress.html.twig', [
            'form' => $form->createView()
        ]);

    }
    
    #[Route('/compte/delete/adresse/{id}', name: 'app_account_delete_adress')]
    public function delete($id)
    {
        $address = $this->entityManager->getRepository(Adresse::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_account_adress');
    }



}
