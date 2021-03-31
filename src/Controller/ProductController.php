<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\Cart\Cart;
use App\Entity\ImageCategory;
use App\Entity\MainCategory;
use App\Form\ProductEditType;
use App\Form\ProductEditImageType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\Product\ServiceProduct;
use App\Repository\MainCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    protected $productRepository, $cart,  $categoryRepository, $serviceProduct, $mainCategoryRepository, $t;

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
     * @Route("/search/{search}", name="product_search", methods={"GET"})
     */
    public function search(Request $request, $search)
    {
        $product = $this->productRepository->findByValue($search);
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
        if(count($_GET) > 0 ){
            
            $productFiltred= [];
            $productInFiltering = [];
            $cara = [];
            $i = 0;
            $listFiltre = [];
            $products = $this->productRepository->findBy(["categorie" =>  $category_product,"mainCategory" => $main_category]);
            // create array of filtre 
            foreach( $_GET as $key => $value){
                $keyClened = str_replace("_", " ", $key);
                $listFiltre[ucfirst($keyClened)] = $value;
            }
            

            foreach($products as $product){
                $description = $product->getDescription();
                foreach($description as $filtre => $value){
                    // delete accent, space and to lower case of filtre 
                    $accents = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A','Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y','Þ'=>'B','ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a','æ'=>'a','ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i','ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T');

                    $filtre_without_accents = strtr($filtre, $accents);

                    // check if we find the filtre activate on description of product 
                    $filtreFinded = array_key_exists($filtre_without_accents, $listFiltre);
                    // var_dump($filtre_without_accents);
                    // var_dump($listFiltre);
                    // exit;
                    if($filtreFinded){

                        $valueInInt = intval($value);
                        $valuesOfFiltre = explode('-', $listFiltre[$filtre_without_accents]);
                        $valueOfFiltreMin = $valuesOfFiltre[0];
                        $valueOfFiltreMax = $valuesOfFiltre[1];
                        if( $valueOfFiltreMin <= $valueInInt && $valueOfFiltreMax >= $valueOfFiltreMax ){
                            if(count($productInFiltering) > 0){
                                foreach($productInFiltering as $productAlreadyTested){
                                    $productTested = $productAlreadyTested['product'];
                                    if($product->getId() ==  $productTested->getId()){

                                        $testSuccededInint = intval($productAlreadyTested['testSucceded']);
                                        $productAlreadyTested['testSucceded'] = $testSuccededInint+1;
                                        $productInFiltering[$i] = $productAlreadyTested;
                                    }
                                    else{
                                        $productTested = ['product'=> $product, 'testSucceded' => 1];
                                        $productInFiltering[] = $productTested;
                                        }
                                    $i++;
                                    }
                            }else{
                                $productTested = ['product'=> $product, 'testSucceded' => 1];
                                $productInFiltering[] = $productTested;
                                }
                         
                        }
                    }
                }
            }

            foreach($productInFiltering as $productAlreadyTested){
                if($productAlreadyTested['testSucceded'] >= count($listFiltre)){
                    $productFiltred[] = $productAlreadyTested['product'];
                }
            }
            return $this->render('product/products.html.twig', [
                'products' => $productFiltred,
                'nbProduct' =>  $this->cart->getNbOfArticle(),
                'categories' => $this->categoryRepository->findAll(),
                'category' =>  $this->categoryRepository->findOneBy(['name' => $category_product ]),
                'main_category' => $main_category,
                'filtre' => true

                
            ]);

        }
        else{
            return $this->render('product/products.html.twig', [
                'products' => $this->productRepository->findBy(
                    [
                        "categorie" =>  $category_product,
                        "mainCategory" => $main_category
                    ]
                ),
                'nbProduct' =>  $this->cart->getNbOfArticle(),
                'categories' => $this->categoryRepository->findAll(),
                'category' =>  $this->categoryRepository->findOneBy(['name' => $category_product ]),
                'main_category' => $main_category,
                
            ]);
        }
    }



    // call from Admin Controller 
    public function editCategorie(Request $request)
    {

        $form = $this->addCategory($request);

        $formD = $this->deleteCategory($request);

        $formMainCategory = $this->addMainCategory($request);

        $formDeleteMainCategory = $this->deleteMainCategory($request);


    

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

            $filtre = $request->get('filtre');
            $unit = $request->get('unit');
            $min = $request->get('min');
            $max = $request->get('max');


            $oldFiltreArray = array();
            for($i=0; $i< sizeof($filtre); $i++){
                $filtreArray = [];
                $filtreArray['filtre'] = $filtre[$i];
                $filtreArray['unit'] = $unit[$i];
                $filtreArray['min'] = $min[$i];
                $filtreArray['max'] = $max[$i];                
                $oldFiltreArray[] = $filtreArray;
            }

            $category->setFiltre($oldFiltreArray);

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
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/{id}/edit", name="product_edit")
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductEditType::class, $product);
        $form->handleRequest($request);

        $formImage =  $this->createForm(ProductEditImageType::class, $product);
        $formImage->handleRequest($request);


        if($formImage->isSubmitted() && $formImage->isValid())
        {  
            $imageMain = $formImage->get('image')->getData();
            $imageComplementary = $formImage->get('images')->getData();


            if($imageMain !== null){

                $fichier = md5(uniqid() );
                $nom = $fichier .'.'. $imageMain->guessExtension();
                $imageMain->move($this->getParameter('images_directory'), $nom);
                $product->setImageMain($nom);
            }

            if(count($imageComplementary) > 0 ){
                $arrayOfComplementaryImage = [];

                foreach ($imageComplementary as $image) {
                    
                    $fichier = md5(uniqid() );
                    $imgName = $fichier .'.'. $image->guessExtension();
                    $image->move($this->getParameter('images_directory'), $imgName);
                    $arrayOfComplementaryImage[] = $imgName;
                }

                foreach($product->getComplementaryImage() as $imgName ){
                    $arrayOfComplementaryImage[] = $imgName;
                }
                $product->setComplementaryImage($arrayOfComplementaryImage);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('product/edit.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
                'formImage' => $formImage->createView(),
            ]);

        }
        if ($form->isSubmitted() && $form->isValid()) {
            $nameCar = $request->get('name');
            $car = $request->get('cara');

            $description = array();
            
            for( $i=0; $i< sizeof($nameCar); $i++){
                $name = $nameCar[$i];
                $description[$name] = $car[$i];
            }

            $product->setDescription($description);

            $this->getDoctrine()->getManager()->flush();

            return $this->render('product/edit.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
                'formImage' => $formImage->createView(),
            ]);
        }

       

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'formImage' => $formImage->createView(),
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

        return $this->redirectToRoute('MyApp_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/delete/img/api/{prod}/{imgName}", name="deleteImgComplementary")
     */
    public function deleteImg( Product $prod, string $imgName){

        $arrayImg = $prod->getComplementaryImage() ;
        foreach($arrayImg as $key => $img){
            if($img == $imgName){
                unset($arrayImg[$key]);
            }

            $prod->setComplementaryImage($arrayImg);
        }


        $this->getDoctrine()->getManager()->flush();

       return $this->redirectToRoute('product_edit', [
           'id'=>$prod->getId()
       ]);
    }

}
