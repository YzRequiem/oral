<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', []);
    }

    #[Route('/bio', name: 'app_bio')]   // je set une catégorie rhums bio
    public function bio( ProductRepository $productRepository ): Response // Je récupere le repository de produit
    {
        return $this->render('category/bio.html.twig', [ 
            'products' => $productRepository->findBy(['category' => '106']), // Je récupere les produits de la catégorie bio
        ]);
       
    }

    #[Route('/old', name: 'app_old')]
    public function old( ProductRepository $productRepository ): Response
    {
        return $this->render('category/old.html.twig', [
            'products' => $productRepository->findBy(['category' => '105']), // Je récupere les produits de la catégorie vieux
        ]);
       
    }

    #[Route('/brut', name: 'app_brut')]
    public function brut( ProductRepository $productRepository ): Response
    {
        return $this->render('category/brut.html.twig', [
            'products' => $productRepository->findBy(['category' => '107']),
        ]);
       
    }

    #[Route('/rafine', name: 'app_rafine')]
    public function rafine( ProductRepository $productRepository ): Response
    {
        return $this->render('category/rafine.html.twig', [
            'products' => $productRepository->findBy(['category' => '108']),
        ]);
       
    }

}
