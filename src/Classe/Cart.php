<?php
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Length;

class Cart{
    private $session;
    private $entityManager;
    private $requestStack;
    public function __construct(RequestStack $requestStack,EntityManagerInterface $entityManager){
        $this->requestStack=$requestStack;
        $this->entityManager=$entityManager;
    }

    public function add($id)
    {
        $cart=$this->requestStack->getSession()->get('cart',[]);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id]=1;
        }
        $this->requestStack->getSession()->set('cart',$cart);
    }

    public function decrease($id)
    {
        $cart=$this->requestStack->getSession()->get('cart',[]);
        if($cart[$id]>1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        $this->requestStack->getSession()->set('cart',$cart);
    }

    public function get()
    {
        return $this->requestStack->getSession()->get('cart');
    }
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
    public function delete($id)
    {
        $cart=$this->requestStack->getSession()->get('cart',[]);
        unset($cart[$id]);
        $this->requestStack->getSession()->set('cart',$cart);
    }

    public function getfull()
    {
        $fullCarts=[];
        if($this->get()){

            foreach ($this->get() as $id => $quantity) {
                $fullCarts[]=[
                    'product'=>$this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity'=>$quantity
    
                ];
                
            }
        }
        return $fullCarts;
    }
}