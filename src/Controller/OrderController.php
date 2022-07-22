<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Stripe\Stripe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart): Response
    {
        if(!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('app_account_add_adress');
        }
        $form= $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
     

        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            'fullCarts' => $cart->getfull()
        ]);
    }


    #[Route('/commande/recap', name: 'app_order_recap',methods:'POST')]
    public function add(Cart $cart, Request $request): Response
    {
     
        $form= $this->createForm(OrderType::class,null,[
            'user'=>$this->getUser()
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // save order
            $date = new \DateTimeImmutable('now');
            $carriers = $form->get('carriers')->getData();

            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>'.$delivery->getCompany();
            }

            $delivery_content .= '<br/>'.$delivery->getAdress();
            $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();

            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            // $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            $YOUR_DOMAIN = 'https://127.0.0.1:8000';
            $product_for_stripe=[];

            // save orderDetails
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetail();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);

                $product_for_stripe[]=[
                    'price_data'=>[
                        'currency'=>'CAD',
                        'unit_amount'=>$product['product']->getPrice(),
                        'product_data'=>[
                            'name'=>$product['product']->getName(),
                            'images'=>[$YOUR_DOMAIN."/uploads/images/".$product['product']->getimage()]
                        ],
                    ],
                        'quantity'=>$product['quantity']
                ];
            }
            // $this->entityManager->flush();

            Stripe::setApiKey('sk_test_51LOPieAfFULoJQbjGAjAK2pcQc7AKKej7wOBRIT1ynSijxd0CPddX9KQXcrWCGYmqZCJa6L1ETMErrKA0tuivfok00lWn0bOON');

            

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                  $product_for_stripe
                ]],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
              ]);

          

            return $this->render('order/add.html.twig', [
                'fullCarts' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'stripe_checkout_session'=>$checkout_session->id
            ]);



        }

        return $this->redirectToRoute('app_panier');
    }
}
