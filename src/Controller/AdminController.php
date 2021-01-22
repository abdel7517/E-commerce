<?php 

namespace App\Controller;


use DateTime;
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
     * @Route("/{date}/{orderCode}/{ready}", name="admin_index")
    */
    public function index($date = null,string $orderCode = null, string $ready = null , Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

         // find all orders 
         if($date == 'all' ){
            $orders = $entityManager->getRepository("App\Entity\Order")->findByState(1);
            if($ready == 'true'){
                $orders = $entityManager->getRepository("App\Entity\Order")->findOrderNotReady();
                return $this->render('admin/index.html.twig', [
                    'products' => $this->productRepository->findAll(),
                    'nbProduct'=>  $this->cart->getNbOfArticle(),
                    'categories'=> $this->allCategoryRepository->findAll(),
                    'orders' => $orders,
                    'date' => 's commandes non traitées',
                ]);
            }
            return $this->render('admin/index.html.twig', [
                'products' => $this->productRepository->findAll(),
                'nbProduct'=>  $this->cart->getNbOfArticle(),
                'categories'=> $this->allCategoryRepository->findAll(),
                'orders' => $orders,
                'date' => ' toutes les commandes',
            ]);
            }
            
            // record order like ready
        if($orderCode !== null){
            $order = $entityManager->getRepository("App\Entity\Order")->findByOrderCode($orderCode);
            $order[0]->setReady(true);
            $entityManager->flush();
            $date = $order[0]->getDate();
            return $this->render('admin/index.html.twig', [
                'products' => $this->productRepository->findAll(),
                'nbProduct'=>  $this->cart->getNbOfArticle(),
                'categories'=> $this->allCategoryRepository->findAll(),
                'orders' => $order,
                'date' => ' numéro de commande : ' . $orderCode . ' daté du '. $date->format('d-m-yy')
            ]);
        }

        // find order by her orderCode
        if($request->isMethod('post')){
            $orderCode = $request->get('SingleOrderCode');
            $orders = $entityManager->getRepository("App\Entity\Order")->findByOrderCode($orderCode);
            $date = $orders[0]->getDate();

            return $this->render('admin/index.html.twig', [
                'products' => $this->productRepository->findAll(),
                'nbProduct'=>  $this->cart->getNbOfArticle(),
                'categories'=> $this->allCategoryRepository->findAll(),
                'orders' => $orders,
                'date' => ' numéro de commande : ' . $orderCode . ' daté du '. $date->format('d-m-yy')
            ]);
        }

       


        // for the fist connexion to this route, give the orders for the day 
        if($date == null ){
            $now = new \DateTime();
            $orders = $entityManager->getRepository("App\Entity\Order")->getByDate($now);   
            return $this->render('admin/index.html.twig', [
                'products' => $this->productRepository->findAll(),
                'nbProduct'=>  $this->cart->getNbOfArticle(),
                'categories'=> $this->allCategoryRepository->findAll(),
                'orders' => $orders,
                'date' =>' '.  $now->format('d-m-yy')
            ]);
        }


        $date = DateTime::createFromFormat('Y-m-d', $date);

        $orders = $entityManager->getRepository("App\Entity\Order")->getByDate($date);   
            return $this->render('admin/index.html.twig', [
                'products' => $this->productRepository->findAll(),
                'nbProduct'=>  $this->cart->getNbOfArticle(),
                'categories'=> $this->allCategoryRepository->findAll(),
                'orders' => $orders,
                'date' => ' '. $date->format('d-m-yy')
            ]);



        



        return $this->render('admin/index.html.twig', [
            'products' => $this->productRepository->findAll(),
            'nbProduct'=>  $this->cart->getNbOfArticle(),
            'categories'=> $this->allCategoryRepository->findAll(),
            'orders' => ' '. $orders
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