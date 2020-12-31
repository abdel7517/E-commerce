<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ImageMain;

use App\Form\CategoryType;
use App\Form\Product3Type;
use App\Service\Cart\Cart;
use App\Entity\ImageCategory;
use App\Entity\MainCategory;
use App\Form\ProductEditType;
use App\Form\ProductEditImageType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\Product\ServiceProduct;
use Container1EzGezZ\getProductService;
use App\Repository\AllCategoryRepository;
use App\Repository\MainCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Test\FormInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    protected $productRepository, $cart,  $categoryRepository, $serviceProduct, $mainCategoryRepository;

    public function __construct(ServiceProduct $serviceProduct, ProductRepository $productRepository, Cart $cart, CategoryRepository $categoryRepository, MainCategoryRepository $mainCategoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->categoryRepository = $categoryRepository;
        $this->serviceProduct = $serviceProduct;
        $this->mainCategoryRepository = $mainCategoryRepository;
    }


    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function mainCategoryPage()
    {
        return $this->render(
            "category/mainCategory.html.twig",
            [
                'nbProduct' =>  $this->cart->getNbOfArticle(),
                'categories' => $this->mainCategoryRepository->findAll()
            ]
        );
    }

    // /**
    //  * @Route("/category/{category}", name="product_category", methods={"GET"})
    //  */
    // public function categoryPage(String $category)
    // {
    //     return $this->render(
    //         "category/category.html.twig",
    //         [
    //             'nbProduct' =>  $this->cart->getNbOfArticle(),
    //             'categories' => $this->categoryRepository->findByMainCategory($category),
    //             'categorie' => $category
    //         ]
    //     );
    // }


   /**
     * @Route("/category", name="product_category", methods={"GET"})
     */
    public function categoryPage()
    {
        return $this->render(
            "index.html.twig",
            [
                'nbProduct' =>  $this->cart->getNbOfArticle(),
                'categories' => $this->categoryRepository->findAll(),
            ]
        );
    }


    /**
     * @Route("/search", name="product_search", methods={"GET"})
     */
    public function search(Request $request)
    {
        $product = $this->productRepository->findByValue($request->get('search'));
        return $this->render(
            'product/search.html.twig',
            [
                'products' => $product,
                'nbProduct' =>  $this->cart->getNbOfArticle(),
            ]
        );
    }


    /**
     * @Route("/category/all/{category_product}/{main_category}", name="product_products", methods={"GET"})
     */
    public function productOfCategory(string $category_product, String $main_category): Response
    {

        return $this->render('product/products.html.twig', [
            'products' => $this->productRepository->findBy(
                [
                    "categorie" =>  $category_product,
                    "mainCategory" => $main_category
                ]
            ),
            'nbProduct' =>  $this->cart->getNbOfArticle(),
            'categories' => $this->categoryRepository->findAll(),
            'category' =>  $category_product,
            'main_category' => $main_category,
        ]);
    }



    // call from Admin Controller 
    public function editCategorie(Request $request)
    {

        $form = $this->addCategory($request);

        $formD = $this->deleteCategory($request);

        $formMainCategory = $this->addMainCategory($request);

        $formDeleteMainCategory = $this->deleteMainCategory($request);


        if (($form->isSubmitted() && $form->isValid()) || ($formD->isSubmitted()  &&  $formD->isValid())) {
            return $this->redirectToRoute('admin_add');
        }



        return $this->render('admin/newCategory.html.twig', [
            'form' => $form->createView(),
            'formD' => $formD->createView(),
            'formMain' => $formMainCategory->createView(),
            'nbProduct' =>  $this->cart->getNbOfArticle(),
            'categories' => $this->categoryRepository->findAll(),
            'formDMain' => $formDeleteMainCategory->createView()
        ]);
    }

    // call from editCategorie 
    public function deleteCategory(Request $request)
    {
        $category = new Category;
        $formD = $this->createForm("App\Form\CategoryDeleteType");
        $formD->handleRequest($request);

        if ($formD->isSubmitted() && $formD->isValid()) {
            $categoryName =  $formD->get('categorie')->getData();

            $name = $categoryName->getName();
            $categoryMain = $categoryName->getMainCategory();

            $entityManager = $this->getDoctrine()->getManager();
            $category = $this->categoryRepository->findByNameAndMainCategory($name, $categoryMain);
            foreach ($category as $catego) {
                $entityManager->remove($catego);
                $entityManager->flush();
            };
        }


        return $formD;
    }

    // call from editCategorie 
    public function deleteMainCategory(Request $request)
    {
        $category = new Category;
        $formD = $this->createForm("App\Form\CategoryMainDelete");
        $formD->handleRequest($request);

        if ($formD->isSubmitted() && $formD->isValid()) {
            $categoryName =  $formD->get('MainCategorie')->getData();

            $name = $categoryName->getName();

            $entityManager = $this->getDoctrine()->getManager();
            $category = $this->mainCategoryRepository->findOneBy(["name" => $name]);
            $entityManager->remove($category);
            $entityManager->flush();
        }


        return $formD;
    }

    // call from editCategorie
    public function addCategory(Request $request)
    {
        $category = new Category;
        $form = $this->createForm("App\Form\CategoryType", $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =  $form->get('image')->getData();
            $fichier = md5(uniqid() . '.' . $image->guessExtension());
            $image->move($this->getParameter('images_directory'), $fichier);
            $img = new ImageCategory();
            $img->setName($fichier);
            $category->setImage($img);
            $category->setMainCategory($form->get('categoriesPrincipale')->getData()->getName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $form;
    }

    // call from editCategorie
    public function addMainCategory(Request $request)
    {
        $category = new MainCategory;
        $form = $this->createForm("App\Form\CategoryMainType", $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image =  $form->get('image')->getData();
            $fichier = md5(uniqid() . '.' . $image->guessExtension());
            $image->move($this->getParameter('images_directory'), $fichier);
            $img = new ImageCategory();
            $img->setName($fichier);
            $category->setImage($img);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $form;
    }


    /**
     * @Route("/{id}/edit", name="product_edit")
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductEditType::class, $product);
        $form->handleRequest($request);

        $formImage =  $this->createForm(ProductEditImageType::class, $product);
        $formImage->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }
        // if($formImage->isSubmitted() && $formImage->isValid())
        // {  
        //     $entityManager = $this->getDoctrine()->getManager();

        //     $ProductsRepo = $entityManager->getRepository(Product::class);

        //     // Récupération de l'utilisateur (donc automatiquement géré par Doctrine)
        //     $productRepo = $ProductsRepo->find($product->getId());

        //     $productRepo->setImage($formImage->get);


        // }



        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'formImage' => $formImage->createView()
        ]);
    }



    /**
     * @Route("/{id}/{main_category}", name="product_show", methods={"GET"})
     */
    public function show(int $id, string $main_category = "produit"): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $this->productRepository->findOneBy(["id" => $id]),
            'nbProduct' =>  $this->cart->getNbOfArticle(),
            'categories' => $this->categoryRepository->findAll(),
            'main_category' => $main_category

        ]);
    }



    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/delete/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
