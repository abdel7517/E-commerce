<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    protected $productRepository, $cart;

    public function __construct(ProductRepository $productRepository, Cart $cart)
    {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        $message = "";
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error !== null)
        {
            $message = "Mot de passe ou email incorrecte";
        }
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', 
        ['last_username' => $lastUsername, 'error' => $message,
        'nbProduct'=>  $this->cart->getNbOfArticle(),
        
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
