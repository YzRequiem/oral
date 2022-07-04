<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    
    public function __construct(private SluggerInterface $slugger) { }


    public function load(ObjectManager $manager): void
    {

        // je set des rhums bio avec un for
        for ($i = 1; $i <= 16; $i++) {
            $pdtbio = new Product(); // je crée un nouveau produit
            $pdtbio->setName("Rhum bio".$i) // je set le nom du produit
                ->setSlug($this->slugger->slug($pdtbio->getName())) // je set le slug du produit
                ->setDescription("description") // je set la description du produit
                ->setPrice(rand(1, 100)) // je set le prix du produit
                ->setDate(new \DateTime()) // je set la date du produit
                ->setImage("bio".$i.".png") // je set l'image du produit
                ->setCategory($this->getReference('Rhum bio')); // je set la catégorie rhums bio que je recupère 
                                                                // dans la fixture category grace à la référence

            $manager->persist($pdtbio);
        }

        // je set des rhums vieux avec un for
        for ($i = 1; $i <= 16; $i++) {
            $old = new Product();
            $old->setName("Rhum vieu".$i)
                ->setSlug($this->slugger->slug($old->getName()))
                ->setDescription("description")
                ->setPrice(rand(1, 100))
                ->setDate(new \DateTime())
                ->setImage("old".$i.".png")
                ->setCategory($this->getReference('Rhum vieux'));
            

            $manager->persist($old);
        } 
        
        // je set des sucres bruts avec un for
        for ($i = 1; $i <= 16; $i++) {
            $brut = new Product();
            $brut->setName("Sucre brut".$i)
                ->setSlug($this->slugger->slug($brut->getName()))
                ->setDescription("description")
                ->setPrice(rand(1, 100))
                ->setDate(new \DateTime())
                ->setImage("brut".$i.".png")
                ->setCategory($this->getReference('Sucres bruts'));

            $manager->persist($brut);
        }

        // je set des sucres rafinés avec un for
        for ($i = 1; $i <= 16; $i++) {
            $rafin = new Product();
            $rafin->setName("Sucre rafiné".$i)
                ->setSlug($this->slugger->slug($rafin->getName()))
                ->setDescription("description")
                ->setPrice(rand(1, 100))
                ->setDate(new \DateTime())
                ->setImage("rafin".$i.".png")
                ->setCategory($this->getReference('Sucres raffinés'));

            $manager->persist($rafin);
        }

        $manager->flush();
    }
}
