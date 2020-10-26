<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart extends AbstractController
{
    protected $session, $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo)
    {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function getCart(): array
    {
        $panier =  $this->session->get('panier', []);
        $panierwithData = [];
        foreach($panier as $id => $quantity)
        {
            $panierwithData[] = 
            [
                'product'=> $this->productRepo->find($id),
                'quantity'=> $quantity
            ] ;
        }

        return $panierwithData;
    }

    public function getTotalCart(): float 
    {
        $total = 0;
        foreach($this->getCart() as $item)
        {
            $total += $item['product']->getPrice()* $item['quantity'];
         }
      
          return $total;
    }
    
    public function add(int $id, int $nb = 1 )
    {
        $panier = $this->session->get("panier", []);
        if(!empty($panier[$id]))
        {
            $panier[$id] += $nb;
        }
        else
        {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);

    }

    public function getNbOfArticle(): int 
    {

        $total = 0;

        foreach($this->getCart() as $item)
        {
            $total +=  $item['quantity'];
         }

        return $total;
        
    }
    
    public function addQuantity(int $id)
    {
        $panier = $this->session->get('panier');
        foreach($panier as $currentId => $quantity)
        {
           if($currentId === $id)
           {
               $panier[$currentId]++;
           }
        }
        $this->session->set("panier",$panier);
    }

    public function substractQuantity(int $id)
    {
        $panier = $this->session->get('panier');
        foreach($panier as $currentId => $quantity)
        {
            
                if($currentId === $id)
                {

                 
                    if(($panier[$currentId]) > 1 )
                    {
                        $panier[$currentId]--;
                    }
                    else
                    {
                        $this->redirectToRoute("cart_remove", ['id'=> $currentId ] );
                    }
                }
         
          
        }
        $this->session->set("panier",$panier);
    }
}
?>