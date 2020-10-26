<?php 

namespace App\Controller;


use App\Service\Cart\Cart;
use App\Controller\ProductController;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\Product\ServiceProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
 
    protected $product,$productRepository, $cart, $allCategoryRepository, $serviceProduct, $categoryRepository ;

    public function __construct( CategoryRepository $categoryRepository ,ServiceProduct $serviceProduct  ,ProductController $product, Cart $cart, CategoryRepository $allCategoryRepository, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->product = $product;
        $this->allCategoryRepository = $allCategoryRepository;
        $this->serviceProduct = $serviceProduct;   
        $this->categoryRepository = $categoryRepository; 
    }

     /**
     * @Route("/", name="admin_index")
    */
    public function index(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $orders = $entityManager->getRepository("App\Entity\Order")->findByState(1);

        return $this->render('admin/index.html.twig', [
            'products' => $this->productRepository->findAll(),
            'nbProduct'=>  $this->cart->getNbOfArticle(),
            'categories'=> $this->allCategoryRepository->findAll(),
            'orders' => $orders
        ]);
    } 


      /**
     * @Route("/category", name="admin_category", methods={"GET"})
     */
    public function categoryPage()
    {
        return $this->render("admin/allCategory.html.twig", 
        ['nbProduct'=>  $this->cart->getNbOfArticle(),
        'categories'=> $this->categoryRepository->findAll()]);
    }

     /**
     * @Route("/category/{category}", name="admin_product_category", methods={"GET"})
     */
    public function productOfCategory(string $category): Response
    {
        return $this->render('admin/productCategory.html.twig', [
            'products' => $this->productRepository->findBy(["categorie" => $category]),
            'nbProduct'=>  $this->cart->getNbOfArticle(),
            'categories'=> $this->categoryRepository->findAll(),
            'category'=> $category
            ]);
    }
    

    


    /**
     * @Route("/add", name="admin_add")
    */
    public function add(Request $request)
    {
       return $this->serviceProduct->add($request);
    }   

   
    
    /**
     * @Route("/newCategory", name="admin_edit_category")
     */
    public function editCategorie(Request $request)
    {
       
        return $this->product->editCategorie($request);

        
    }

    
     /**
     * @Route("/product/{id}/{category}", name="admin_show_product", methods={"GET"})
     */
    public function show(int $id, string $category = "produit"): Response
    {
        return $this->render('admin/show.html.twig', [
            'product' => $this->productRepository->findOneBy(["id"=> $id]),
            'nbProduct'=>  $this->cart->getNbOfArticle(),
            'categories'=> $this->categoryRepository->findAll(),
            'category'=> $category

        ]);
    }

    
}