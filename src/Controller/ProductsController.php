<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits', name: 'produits_')]

class ProductsController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index( ProductRepository $productRepository): Response
    {
        return $this->render('products/index.html.twig',[
            'controller_name' => 'ProductsController',
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details( Product $product, ProductRepository $productRepository): Response
    {   
        return $this->render('products/details.html.twig', compact('product')); // compact('products') permet de passer la variable products à la vue
    }

   
        
    

}
