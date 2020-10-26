<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Service\Cart\Cart;
use Stripe\BaseStripeClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{

    protected $allCategoryRepository, $cart, $orderRepository;

    public function __construct(CategoryRepository $allCategoryRepository, Cart $cart, OrderRepository $orderRepository)
    {
    
        $this->allCategoryRepository = $allCategoryRepository;
        $this->cart = $cart;
        $this->orderRepository = $orderRepository;
    }
    /**
     * @Route("/user", name="user_index")
     */
    public function index()
    {
        //Check if user is loged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/index.html.twig', [
        'items'=>  $this->cart->getCart(),
         'total'=> $this->cart->getTotalCart(),
         'nbProduct'=> $this->cart->getNbOfArticle(),
         'categories'=> $this->allCategoryRepository->findAll()
        ]);
    }

     /**
     * @Route("/history", name="user_history")
     */
    public function getOrders()
    {


        // get id of current user
        $user_id = $this->getUser()->getId();

        //get the repository of curent user 
        $entityManager = $this->getDoctrine()->getManager();

        
        // $user = $entityManager->getRepository(User::class)->find($user_id);
        // $orders = $user->getOrders();
        $orders = $this->orderRepository->findById($user_id, 1);


        return $this->render('user/history.html.twig', [
            'items'=>  $this->cart->getCart(),
         'total'=> $this->cart->getTotalCart(),
         'nbProduct'=> $this->cart->getNbOfArticle(),
         'categories'=> $this->allCategoryRepository->findAll(),
         'orders' => $orders
        ]);
    }


    

}
