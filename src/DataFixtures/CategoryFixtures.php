<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // je set ma categorie parent 'Mes rhums'
        $parentRhums = new Category();
        $parentRhums->setName("Mes rhums")
                    ->setSlug($this->slugger->slug($parentRhums->getName()))
                    ->setDescription("description")
                    ->setImage("parentRhums.jpg");
        $manager->persist($parentRhums);

        // je set ma categorie  parent 'Mes sucres'
        $parentSucres = new Category();
        $parentSucres->setName("Mes sucres")
                    ->setSlug($this->slugger->slug($parentSucres->getName()))
                    ->setDescription("description")
                    ->setImage("parentSucres.jpg");
        $manager->persist($parentSucres);

        // je set une catégorie rhums vieux 

        
            $old = new Category();
            $old->setName("Rhum vieux")
                ->setSlug($this->slugger->slug($old->getName()))
                ->setDescription("description")
                ->setParent($parentRhums)
                ->setImage("old.jpg");
            $manager->persist($old);
            $this->addReference('Rhum vieux', $old);
      

        // je set une catégorie rhums bio 

        
            $bio = new Category();
            $bio->setName("Rhum bio")
                ->setSlug($this->slugger->slug($bio->getName()))
                ->setDescription("description")
                ->setParent($parentRhums)
                ->setImage("bio.jpg");
                
                $manager->persist($bio);
                $this->addReference('Rhum bio', $bio);
        

        // je set une catégorie surcres bruts 

  
            $brut = new Category();
            $brut->setName("Sucres bruts")
                ->setSlug($this->slugger->slug($brut->getName()))
                ->setDescription("description")
                ->setParent($parentSucres)
                ->setImage("brut.jpg");
            $manager->persist($brut);
            $this->addReference('Sucres bruts', $brut);
        

        // je set une catégorie surcres rafinés 

       
            $rafine = new Category();
            $rafine->setName("Sucres raffinés")
                ->setSlug($this->slugger->slug($rafine->getName()))
                ->setDescription("description")
                ->setParent($parentSucres)
                ->setImage("rafine.jpg");
            $manager->persist($rafine);
            $this->addReference('Sucres raffinés', $rafine);
        


        $manager->flush();
    }
}
