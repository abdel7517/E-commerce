<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Controller\ProductController;
use App\Repository\MainCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyAppController extends AbstractController
{

    protected $cart,  $mainCategoryRepository, $productController;

    public function __construct(Cart $cart,  MainCategoryRepository $mainCategoryRepository, ProductController $productController)
    {
        $this->cart = $cart;
        $this->mainCategoryRepository = $mainCategoryRepository;
        $this->productController = $productController;
    }

    /**
     * @Route("/", name="MyApp_index")
    */
    public function index()
    {
        return $this->productController->categoryPage();
    }

        
    
    /** 
     * @Route("/admin", name="MyApp_admin")
     */
    public function admin()
    {
        return $this->render("admin/index.html.twig", 
        ['nbProduct'=> $this->cart->getNbOfArticle()
        ]);
        
    }
    
    /**
     * @Route("/good/{orderCode}", name="MyApp_good")
    */
    public function good(Request $request, string $orderCode)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ID = $request->get('t');
    
    
        $orders = $entityManager->getRepository("App\Entity\Order")->findByValue($orderCode);
        foreach($orders as $order){
            $order->setState(1);
            $entityManager->persist($order);
            $entityManager->flush();
            
        }
    
    
        return $this->render(
            "cart/good.html.twig",
            [
                'nbProduct' => $this->cart->getNbOfArticle(),
                'mainCategory' => $this->mainCategoryRepository->findAll(),
                'orderCode' => $orderCode,
                'id' => $ID,
            ]
        );
    }
    
    
     /**
     * @Route("/noGood", name="MyApp_noGood")
     */
    public function noGood(Request $request)
    {
    
        return $this->render(
            "cart/noGood.html.twig",
            [
                'nbProduct' => $this->cart->getNbOfArticle(),
                'mainCategory' => $this->mainCategoryRepository->findAll(),
            ]
        );
    }
}
