<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Entity\PromoCode;
use App\Service\Cart\Cart;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\VarDumper\VarDumper;

class ServiceProduct extends AbstractController
{
    protected $cart;

    public function __construct(Cart $Cart)
    {
        $this->cart = $Cart;
    }

    public function add(Request $request)
    {
        $product = new Product();
        $form = $this->createForm("App\Form\Product3Type", $product);
        $form->handleRequest($request);
        $promoCode = new PromoCode();
        $formPromoCode = $this->createForm("App\Form\PromoCodeType", $promoCode);
        $formPromoCode->handleRequest($request);
        $formPromoCodeDelete = $this->createForm("App\Form\PromoCodeDeleteType", $promoCode);
        $formPromoCodeDelete->handleRequest($request);

        if ($formPromoCodeDelete->isSubmitted() && $formPromoCodeDelete->isValid()) {
            $this->deletePromoCode($request, $formPromoCodeDelete);
            
        }

        if ($formPromoCode->isSubmitted() && $formPromoCode->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($promoCode);
            $entityManager->flush();
            
        }

       

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setImages($form, $product);
           
            $categoryName =  $form->get('categorie')->getData();

            $nameCar = $request->get('name');
            $car = $request->get('cara');

            $description = array();
            
           if($nameCar !== null){
                for( $i=0; $i< sizeof($nameCar); $i++){
                    $name = $nameCar[$i];
                    $description[$name] = $car[$i];
                }
           }

            $name = $categoryName->getName();
            $categoryMain = $categoryName->getMainCategory();

            $product->setMainCategory($categoryMain);
            $product->setCategorie($name);
            $product->setDescription($description);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            // return $this->redirectToRoute('admin_index');
            return $this->render('admin/new.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
                'nbProduct' => $this->cart->getNbOfArticle(),
                'formPromoCode' => $formPromoCode->createView()
            ]);
        }

        
        // get the caracteritique of categorys 
        $categoryRepo = $this->getDoctrine()->getRepository('App\Entity\Category')->findAll();
        $listOfCaracteristic = [];
        foreach($categoryRepo as $category){
            $nameCategory = $category->getName();
            $listOfCaracteristic[$nameCategory] = $category->getFiltre();
        }


        return $this->render('admin/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'nbProduct' => $this->cart->getNbOfArticle(),
            'formPromoCode' => $formPromoCode->createView(),
            'formPromoCodeDelete' => $formPromoCodeDelete->createView()


        ]);
    }

    
    public function setImages(FormInterface $form, $product ){
        $images = $form->get('images')->getData();
        $arrayOfComplementaryImage = array();

        foreach ($images as $image) {
            $fichier = md5(uniqid() );
            $nom = $fichier .'.'. $image->guessExtension();
            $image->move($this->getParameter('images_directory'), $nom);
            $arrayOfComplementaryImage[] = $nom;
        }
        $product->setComplementaryImage($arrayOfComplementaryImage);

        $imageMain = $form->get('image')->getData();

        $fichier = md5(uniqid() );
        $nom = $fichier .'.'. $imageMain->guessExtension();
        $imageMain->move($this->getParameter('images_directory'), $nom);
        $product->setImageMain($nom);

    }

    public function deletePromoCode($request, $form){
        $entityManager = $this->getDoctrine()->getManager();
        $code = $form->get('promoCode')->getData();

        $promoCodeRepo = $this->getDoctrine()->getRepository('App\Entity\PromoCode')->findOneBy(['Code' => $code->getCode() ]);

        $entityManager->remove($promoCodeRepo);
        $entityManager->flush();
    }  
}
