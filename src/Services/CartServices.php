<?php
namespace App\Services;

use App\Repository\ProductsRepository;
use App\Controller\CartController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartServices
{
    private $session;
    private $repoProduct;

    public function __construct(SessionInterface $session, ProductsRepository $repoProduct)
    {
        $this->$session = $session;
        $this->$repoProduct = $repoProduct;

    }

    //fontion ajout au panier

    public function add(ProductsRepository $repoProduct)
    {
        $cart =  $this->$session->get('cart',[]);
        $productId = $repoProduct->getId();

        if (!isset($cart[$productId]))
        {
            $cart[$productId]=[
                'quantity' => 0,
                'product' => $product,

            ];

        }

        $cart[$productId]['quantity']++;
        $this->session->set('cart', $cart);
    }

    // fonction update 

    public function remove(ProductsRepository $repoProduct)
    {
        $cart = $this->session->get('cart', []);
        $productId = $repoProduct->getId();

        if (isset($cart[$productId])){

            if($cart[$productId]['quantity'] > 1){
                $cart[$productId]['quantity']--;

            }else {
                unset($cart[$productId]);
            }
            $this->session->set('cart', $cart);

        }



    }   


    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }



    // fonction créer  un objet qui contient en plus du panier en session la quantité
}