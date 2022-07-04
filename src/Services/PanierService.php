<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService
{
    private $requestStack;
    private $productRepository;
    
    // Constructeur injecte le service RequestStack et ProductRepository
    public function __construct(RequestStack $requestStack, ProductRepository $productRepository,
     ManagerRegistry $doctrine, OrderRepository $orderRepository){
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
        $this->doctrine = $doctrine;
        $this->orderRepository = $orderRepository;
    }

    // Retourne le panier
    public function getProduits(){
        $session = $this->requestStack->getCurrentRequest();
        $panier = $session->getSession()->get('panier', []);
        $produits = [];
        foreach ($panier as $id => $quantity) {
                $produit = $this->productRepository->find($id);
                $produit->qtite = $quantity;
                $produits[] = $produit;
        }
        return $produits;
    }
    
    // Ajoute un produit au panier
    public function ajouter($id){ 
        $session = $this->requestStack->getSession(); 
        $panier = $session->get('panier'); 
        if(isset($panier[$id])){ 
            $panier[$id] = $panier[$id] + 1;
        }
        else{
            $panier[$id] = 1;
        }
        
        $session->set('panier', $panier);
    }

    // Supprime un produit du panier
    public function supprimer($id){
        $session = $this->requestStack->getCurrentRequest();
        $panier = $session->getSession()->get('panier', []);
        if(array_key_exists($id, $panier)){ 
            unset($panier[$id]);
        }
        $session->getSession()->set('panier', $panier);
    }

    // Incrémente la quantité d'un produit du panier
    public function increment($id){
        $session = $this->requestStack->getCurrentRequest();
        $panier = $session->getSession()->get('panier', []);
        if(array_key_exists($id, $panier)){
            $panier[$id] = $panier[$id] + 1;
        }
        $session->getSession()->set('panier', $panier);
    }

    // Décrémente la quantité d'un produit du panier
    public function decrement($id){
        $session = $this->requestStack->getCurrentRequest(); // On récupère la session
        $panier = $session->getSession()->get('panier', []); // On récupère le panier
        // si quantité < 1 , on supprime le produit du panier
        if(array_key_exists($id, $panier)){ // si le produit existe dans le panier
            if($panier[$id] > 1){ // si la quantité est supérieure à 1
                $panier[$id] = $panier[$id] - 1; // on décrémente la quantité
            }
            else{
                unset($panier[$id]);    // on supprime le produit du panier
            }
        }
        $session->getSession()->set('panier', $panier); // on met à jour le panier
    }

    // envoyer le panier dans la table commande a l'etat 0 
    public function sendPanier($user){
            $session = $this->requestStack->getSession(); // On recupère la session panier
            $panier = $session->get('panier');
            if(empty($panier )){
                return false ;
            }
            else{
                    $order = new Order();
                    $order->setUser($user);
                    $order->setEtat(0);
                    $order->setDate(new \DateTime());
                
                    $em = $this->doctrine->getManager();
                    
                    foreach ($panier as $id => $quantity) {
                        $orderDetail = new OrderDetail();
                        $orderDetail->setOrder($order);
                        $orderDetail->setProduct($this->productRepository->find($id));
                        $orderDetail->setQuantite($quantity);
                        $orderDetail->setPrix($this->productRepository->find($id)->getPrice());
                        $em->persist($orderDetail);
                    }
                    $em->persist($order);
                    $em->flush();
                    $session->remove('panier');
                }  
    }

    public function payer($user)
    {
        // SELECT * FROM commande WHERE user_id = $user->getId() AND etat = 0
        // => renvoie la commande d'id 12 par exemple
        $commande = $this->orderRepository->findOneBy(['user' => $user, 'etat' => 0]);
        if($commande){
            // update commande set etat = 1 where id =12
            $commande->setEtat(1);
            $this->doctrine->getManager()->flush();
            // on recupere le pannier en session    
            $this->requestStack->getSession()->remove('panier'); // On supprime le panier

            return true;
        }
        return false;
    }
}


