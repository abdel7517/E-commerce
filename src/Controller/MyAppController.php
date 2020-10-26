<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart\Cart;
use App\Controller\AdminController;
use App\Repository\AllCategoryRepository;
use App\Repository\MainCategoryRepository;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyAppController extends AbstractController
{

    protected $cart, $allCategoryRepository, $mainCategoryRepository;

    public function __construct(Cart $cart, AllCategoryRepository $allCategoryRepository, MainCategoryRepository $mainCategoryRepository)
    {
        $this->cart = $cart;
        $this->allCategoryRepository = $allCategoryRepository;
        $this->mainCategoryRepository = $mainCategoryRepository;
    }
    /**
     * @Route("/good", name="MyApp_good")
     */
    public function good(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $orderCode = $request->get('s');
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
                'categories' => $this->allCategoryRepository->findAll(),
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
                'categories' => $this->allCategoryRepository->findAll(),
                'mainCategory' => $this->mainCategoryRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/", name="MyApp_index")
     */
    public function index()
    {
        



        return $this->render(
            "index.html.twig",
            [
                'nbProduct' => $this->cart->getNbOfArticle(),
                'categories' => $this->allCategoryRepository->findAll(),
                'mainCategory' => $this->mainCategoryRepository->findAll(),
            ]
        );
    }


    /** 
     * 
     * @Route("/admin", name="MyApp_admin")
     */
    public function admin()
    {
           return $this->render("admin/index.html.twig", 
            ['nbProduct'=> $this->cart->getNbOfArticle(),
            'categories'=> $this->allCategoryRepository->findAll()]);

    }

    /** 
     * 
     * @Route("/contact", name="MyApp_contact")
     */
    public function contact()
    {
           return $this->render("contact/index.html.twig", 
            ['nbProduct'=> $this->cart->getNbOfArticle(),
            'categories'=> $this->allCategoryRepository->findAll()]);
    }


}
