<?php

namespace App\Controller;

use App\Services\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    private $panierService;
    
    // Constructeur injecte le service PanierService 

    public function __construct(PanierService $panierService){
        $this->panierService = $panierService; 
    }

    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        $produits = $this->panierService->getProduits(); // On récupère les produits du panier

        return $this->render('panier/index.html.twig', [
            'produits' => $produits, // On passe les produits au template
        ]);
    }

    // Ajoute un produit au panier
    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter')]
    public function ajouter($id): Response
    {
        $this->panierService->ajouter($id);

        return $this->redirectToRoute('app_panier');
    }

    // Supprime un produit du panier
    #[Route('/panier/supprimer/{id}', name: 'app_panier_supprimer')]
    public function supprimer($id): Response
    {
        $this->panierService->supprimer($id);

        return $this->redirectToRoute('app_panier');
    }

    // Incrémente la quantité d'un produit du panier
    #[Route('/panier/increment/{id}', name: 'app_panier_increment')]
    public function increment($id): Response
    {
        $this->panierService->increment($id);

        return $this->redirectToRoute('app_panier');
    }
    
    // Décrémente la quantité d'un produit du panier
    #[Route('/panier/decrement/{id}', name: 'app_panier_decrement')]
    public function decrement($id): Response
    {
        $this->panierService->decrement($id);

        return $this->redirectToRoute('app_panier');
    }

    // envoyer le panier vers la base de données
    #[Route('/panier/envoyer', name: 'app_panier_envoyer')]
    public function envoyer(): Response
    {
         // si il n'y a pas d'utilisateur connecté je redirige vers la page de connexion
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }

        // Si le panier est vide on redirige vers la page panier
        if(empty($panier)){
            return $this->redirectToRoute('app_panier');
        }
        
        $user = $this->getUser();
        // on enregistre la commande si le panier n'est pas vide
        $this->panierService->sendPanier($user);
        return $this->render('panier/paiement.html.twig');
      
    }

    //valider le paiement
    #[Route('/panier/valider', name: 'app_panier_valider')]
    public function valider(): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $this->panierService->payer($user);
        return $this->redirectToRoute('main'); 
    }
}
