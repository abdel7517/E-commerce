<?php

namespace App\Controller;

use DateTime;
use Curl\Curl;
use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use App\Form\OrderType;
use App\Service\Cart\Cart;
use App\Service\Contact\Mail;
use App\Repository\CategoryRepository;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CartController extends AbstractController
{

    protected $allCategoryRepository, $cart, $stripeClient, $client, $mailer;

    public function __construct(CategoryRepository $allCategoryRepository, Cart $cart, HttpClientInterface $client, Mail $mailer)
    {
        $this->allCategoryRepository = $allCategoryRepository;
        $this->cart = $cart;
        $this->client = $client;
        $this->mailer = $mailer;
    }


    /**
     * @Route("/panier/add/{id}/{category}", name="cart_add")
     */
    public function add(int $id, string $category)
    {

        $this->cart->add($id);
        if ($category == 'produit') {
            return $this->redirectToRoute("product_show", ["id" => $id]);
        }
        return $this->redirect('/product/category/' . $category);
    }
    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove(int $id, Cart $cart)
    {
        $cart->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/addQ/{id}", name="cart_addQ")
     */
    public function addQuantity(int $id, Cart $cart)
    {
        $this->cart->addQuantity($id);
        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/panier/subQ/{id}", name="cart_subQ")
     */
    public function substractQuantity(int $id)
    {
        $this->cart->substractQuantity($id);
        return $this->redirectToRoute("cart_index");
    }


    /**
     *  @Route("/panier", name="cart_index"))
     */
    public function valid(Request $request)
    {
        //Check if user is loged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $order = new Order();

        $form = $this->createForm(
            OrderType::class,
            $order,
            [
                'action' => $this->generateUrl('cart_pay'),
            ]
        );


        return $this->render('cart/order.html.twig', [
            'registrationForm' => $form->createView(),
            'nbProduct' =>  $this->cart->getNbOfArticle(),
            'categories' => $this->allCategoryRepository->findAll(),
            'form_livraison' => $form->createView(),
            'items' =>  $this->cart->getCart(),
            'total' => $this->cart->getTotalCart(),
        ]);
    }

 


    /**
     * @Route("/panier/pay", name="cart_pay")
     */
    public function pay(Request $request)
    {

        
        $order = new Order();
        $orderCode = uniqid();

        $form = $this->createForm(
            OrderType::class,
            $order,
            [
                'action' => $this->generateUrl('MyApp_good', ['orderCode' => $orderCode]),
            ]
        );
        $form->handleRequest($request);

        $price =  $this->cart->getTotalCart();
        // if($form->get('expedition')->getData() === "livraison")
        // {
        //     $price += 90;
        // }

        // // 'Basic NTdkNWQ1NzAtZDExZS00NzMzLWIxMzQtNDA3MjQzNTdkYjU3OmVLYjNoZkVLelE1SDM2WGU3MjRWR0RUMzI1OTUwVg=='
        // $response = $this->client->request(
        //     'POST',
        //     // 'https://www.vivapayments.com/api/orders/',
        //     'https://demo.vivapayments.com/api/orders/',
        //     [
        //         'headers' => ['authorization' => 'Basic ZDA2ODhjMGItYTk4MC00OTI2LTgyNmYtZDFlMWFlMTkwOGI5Oks4bzo4Xg=='],
        //         'body' => [
        //             'amount' => ($price * 100),
        //             'email' => $this->getUser()->getEmail(),
        //             'fullname' => $this->getUser()->getName(),
        //             'requestLang' => 'fr'

        //         ],

        //     ]
        // );

        // $content = $response->getContent();
        // $content = $response->toArray();
        // $orderCode = $content['OrderCode'];
        // $url = 'https://www.vivapayments.com/web/checkout?ref='. $orderCode . '&Lang=fr';
        // $url = 'https://demo.vivapayments.com/web/checkout?ref=' . $orderCode . '&Lang=fr';



        //get the repository of curent user 
        $entityManager = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();

        $user = $entityManager->getRepository(User::class)->find($user_id);


        if ($form->isSubmitted() && $form->isValid()) {

            $order->setName($form->get('name')->getData());
            $order->setNumberAdress($form->get('numberAdress')->getData());
            $order->setNameAdress($form->get('nameAdress')->getData());
            $order->setPostalCode($form->get('postalCode')->getData());
            $order->setCountry($form->get('country')->getData());
            $order->setDate(new DateTime());
            $order->setCart($this->cart->getCart());
            // $order->setExpedition($form->get('expedition')->getData());
            $order->setExpedition('récupération');
            $order->setOrderCode($orderCode);
            $order->setState(0);



            $user->addOrder($order);
            $user->setName($form->get('name')->getData());


            $entityManager->persist($user);
            $entityManager->flush();

            $this->mailer->send($user->getEmail() ,$form->get('name')->getData(), $orderCode);
            $this->mailer->notifAdmin();

        }

        return $this->redirectToRoute('MyApp_good', ['orderCode' => $orderCode]);
        // return $this->redirect($url);

    }
}
