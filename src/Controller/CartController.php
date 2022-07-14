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
   
    #[Route('/mon-panier', name: 'app_panier')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'fullCarts' => $cart->getfull(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_add_to_cart')]
    public function add(Cart $cart, $id): Response
    {
       $cart->add($id);
       return $this->redirectToRoute('app_panier');
    }

    #[Route('/cart/decrease/{id}', name: 'app_decrease_from_cart')]
    public function decrease(Cart $cart, $id): Response
    {
       $cart->decrease($id);
       return $this->redirectToRoute('app_panier');
    }

    #[Route('/cart/remove', name: 'app_remove_cart')]
    public function remove(Cart $cart): Response
    {
       $cart->remove();
       return $this->redirectToRoute('app_products');
    }
    #[Route('/cart/delete/{id}', name: 'app_delete_from_cart')]
    public function delete(Cart $cart,$id): Response
    {
       $cart->delete($id);
       return $this->redirectToRoute('app_panier');
    }
}
