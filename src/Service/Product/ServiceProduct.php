<?php

namespace App\Service\Product;

use App\Entity\Image;
use App\Entity\ImageMain;
use App\Entity\Product;
use App\Form\Product3Type;
use App\Service\Cart\Cart;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setImages($form, $product);
           
            $categoryName =  $form->get('categorie')->getData();

            $name = $categoryName->getName();
            $categoryMain = $categoryName->getMainCategory();

            $product->setMainCategory($categoryMain);
            $product->setCategorie($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            // return $this->redirectToRoute('admin_index');
            return $this->render('admin/new.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
                'nbProduct' => $this->cart->getNbOfArticle(),
            ]);
        }
        return $this->render('admin/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'nbProduct' => $this->cart->getNbOfArticle(),

        ]);
    }

    
    public function setImages(FormInterface $form, $product ){
        $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $fichier = md5(uniqid() );
                $nom = $fichier .'.'. $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $nom);
                $img = new Image();
                $img->setName($nom);
                $product->addImage($img);
            }

            $imageMain = $form->get('image')->getData();

            $fichier = md5(uniqid() );
            $nom = $fichier .'.'. $imageMain->guessExtension();
            $imageMain->move($this->getParameter('images_directory'), $nom);
            $img = new Imagemain();
            $img->setName($nom);
            $product->setImage($img);

            


    }
}
