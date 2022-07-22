<?php

namespace App\Controller;

use App\Classe\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }


    #[Route('/stripe/create-checkout-session', name: 'app_stripe_ceckout_session', methods: ['POST'])]
    public function createCharge(Request $request, Cart $cart)
    {
        Stripe::setApiKey('sk_test_51LOPieAfFULoJQbjGAjAK2pcQc7AKKej7wOBRIT1ynSijxd0CPddX9KQXcrWCGYmqZCJa6L1ETMErrKA0tuivfok00lWn0bOON');
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';
        $product_for_stripe = [];
        foreach ($cart->getFull() as $product) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'CAD',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN . "/uploads/images/" . $product['product']->getimage()]
                    ],
                ],
                'quantity' => $product['quantity']
            ];
        }


        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                $product_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);


        return $this->redirect($checkout_session->url,303);
    }
}
