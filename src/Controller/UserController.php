<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Service\Contact\Mail;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    protected $allCategoryRepository, $cart, $orderRepository, $mailer, $userRepo;

    public function __construct(CategoryRepository $allCategoryRepository, Cart $cart, OrderRepository $orderRepository, Mail $mailer, UserRepository $userRepo)
    {
    
        $this->allCategoryRepository = $allCategoryRepository;
        $this->cart = $cart;
        $this->orderRepository = $orderRepository;
        $this->mailer = $mailer;
        $this->userRepo = $userRepo;
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
        $orders = $this->orderRepository->findBy([ 'user'=> $user_id,'state' => 1], ["date"=>'DESC']);


        return $this->render('user/history.html.twig', [
         'items'=>  $this->cart->getCart(),
         'total'=> $this->cart->getTotalCart(),
         'nbProduct'=> $this->cart->getNbOfArticle(),
         'categories'=> $this->allCategoryRepository->findAll(),
         'orders' => $orders
        ]);
    }

    /**
     * @Route("/setPass/{id}", name="user_mailSetPass")
     */
    public function setPass(string $id, Request $request, UserPasswordEncoderInterface $passwordEncoder ){

        
        if($request->isMethod('POST')){
            if($_POST['pass'] && $_POST['cpass']){
                $entityManager = $this->getDoctrine()->getManager();
                $user = $this->userRepo->findOneBy(['id' => $id]);
                $passWord = $passwordEncoder->encodePassword($user, $_POST['pass']);
                $user->setPassword( $passWord);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->render('user/resetPass.html.twig', [
                    'id' => $id,
                    'nbProduct'=> $this->cart->getNbOfArticle(),
                    'message' => ' Votre mot de passe à bien était modifier '
                    ]);
            }

        }
        return $this->render('user/resetPass.html.twig', [
            'id' => $id,
            'nbProduct'=> $this->cart->getNbOfArticle(),
            'message' => ''
            ]);
       
    }

     /**
     * @Route("/sendMailPass", name="user_setPass")
     */
    public function mailSetPass(){

        $request = Request::createFromGlobals();
        
        if($request->isMethod('POST')){

            $mail = $_POST['mail'];
            $user = $this->userRepo->findOneBy(['email' => $mail]);
            if($user !== null ){

                $l = $this->generateUrl('user_mailSetPass', ['id' => $user->getId()], UrlGenerator::ABSOLUTE_URL);
                $this->mailer->setPass($mail, $l );
                return $this->render('user/forgotPass.html.twig', ['nbProduct'=> $this->cart->getNbOfArticle(), 'message'=> 'Un mail de reinitialisation à eté envoyer']);
            }
            return $this->render('user/forgotPass.html.twig', ['nbProduct'=> $this->cart->getNbOfArticle(), 'message'=> 'Votre mail n\'est rattaché à aucun compte']);

            
        }
        return $this->render('user/forgotPass.html.twig', ['nbProduct'=> $this->cart->getNbOfArticle(), 'message' => '']);




    }
    

    

}
