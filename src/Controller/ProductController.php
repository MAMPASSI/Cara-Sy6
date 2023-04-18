<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function details(ProductsRepository $ProductsRepository): Response
    {

        return $this->render('product/index.html.twig',[
            'products'=>$ProductsRepository->findAll()
        ] );
    }
    
}
