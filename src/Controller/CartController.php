<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController

{
    
    #[Route('/', name: 'cart_index')]
    public function index(SessionInterface $session, ProductsRepository $repoProduct): Response
    {
        $panier =  $session->get("panier", []);

        $dataPanier = [];
        $total = 0;
        $productIds = array_keys($panier);

        if(!empty($productIds))

        {
            $products = $repoProduct->findBy(['id' => $productIds]);
            foreach($products as $product)
            {
                $id = $product->getId();
                $quantite = $panier[$id];

                $dataPanier[] = [
                    "produit" => $product,
                    "quantity" => $quantite
                ];
    
                $total += $product->getPrice() * $quantite;
    
    
            }


        }

        
        
        return $this->render('cart/index.html.twig', [
            'dataPanier'=>$dataPanier,
            
            'total'=>$total
        ]);
    }


    


    #[Route('/add/{id}', name: 'cart_add')]
    public function add(ProductsRepository $repoProduct,SessionInterface $session, int $id): Response
    {
        $panier = $session->get("panier", []);


        $product = $repoProduct->find($id);

        if($product)

        {
            $productId = $product->getId();

            if(!empty($panier[$productId])){
                $panier[$productId]++;
           }else{
            $panier[$productId] = 1;
            }
            $session->set("panier", $panier);
            
            

        }
        
       
        return $this->redirectToRoute('cart_index');
        
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(ProductsRepository $repoProduct,SessionInterface $session,int $id): Response 
    {
        $panier = $session->get("panier", []);
        $product = $repoProduct->find($id);
        if($product)
        {
            $productId = $product->getId();
            if(!empty($panier[$productId]))
            {
                if($panier[$productId] > 1){
                    $panier[$productId]--;
                }else {
                    unset($panier[$productId]);
                }
    
            }
            $session->set("panier", $panier);


        }
        
        
        

        return $this->redirectToRoute('cart_index');


    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function delete(ProductsRepository $repoProduct,SessionInterface $session,int $id):  Response 
    {
        $panier = $session->get("panier", []);
        $product = $repoProduct->find($id);

        if ($product) {
            $productId = $product->getId();
            if (!empty($panier[$productId])) {
                unset($panier[$id]);
            }

            $session->set("panier", $panier);  
        }
        
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/deleteAll', name: 'cart_deleteAll')]
    public function deleteAll(SessionInterface $session):  Response 
    {
        $session->remove("panier");
        return $this->redirectToRoute('cart_index');
    }
}
