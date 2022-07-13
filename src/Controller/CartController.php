<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    #[Route('/mon-panier', name: 'app_panier')]
    public function index(Cart $cart): Response
    {
        // dd($cart->get());
        $fullCarts=[];
        foreach ($cart->get() as $id => $quantity) {
            $fullCarts[]=[
                'product'=>$this->entityManager->getRepository(Product::class)->findOneById($id),
                'quantity'=>$quantity

            ];
            
        }
        // dd($fullCarts);
        return $this->render('cart/index.html.twig', [
            'fullCarts' => $fullCarts,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_add_to_cart')]
    public function add(Cart $cart, $id): Response
    {
       $cart->add($id);
       return $this->redirectToRoute('app_panier');
    }

    #[Route('/cart/remove', name: 'app_remove_cart')]
    public function remove(Cart $cart): Response
    {
       $cart->remove();
       return $this->redirectToRoute('app_products');
    }
}
