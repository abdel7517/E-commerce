<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Cart\Cart;
use App\Form\RegistrationFormType;
use App\Repository\ProductRepository;
use App\Security\AppAuthAuthenticator;
use App\Repository\AllCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    protected $productRepository, $cart, $allCategoryRepository;

    public function __construct(ProductRepository $productRepository, Cart $cart, AllCategoryRepository $allCategoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->allCategoryRepository = $allCategoryRepository;
        
    }
    
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, ['attr' => ['class'=>'log']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'nbProduct'=>  $this->cart->getNbOfArticle(),
            'categories'=> $this->allCategoryRepository->findAll()


        ]);
    }
}
