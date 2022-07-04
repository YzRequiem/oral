<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {   
        // je set un admin
        $admin = new User(); // je crée un nouvel utilisateur
        $admin->setEmail('admin@admin.fr');     // je set l'email
        $admin->setLastName('Admin');          // je set le nom
        $admin->setFirstName('Admin');        // je set le prénom
        $admin->setCity('Adminville');       // je set la ville
        $admin->setZipcode('64550');       // je set le code postal
        $admin->setAddress('5 rue Admin');      // je set l'adresse
        $admin->setRoles(['ROLE_ADMIN']); // je set le rôle
        $admin->setDate(new \DateTime()); // je set la date de création

        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin')); // je set le mot de passe et je hash le mot de passe
        $manager->persist($admin); // je persiste l'utilisateur

        // je set des utilisateurs avec un for
        for ($i = 0; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user".$i."@user.fr")
                ->setLastName("user".$i)
                ->setFirstName("user".$i)
                ->setCity("userville")
                ->setZipcode("64550")
                ->setAddress("5 rue user")
                ->setRoles(['ROLE_USER'])
                ->setDate(new \DateTime())
                ->setPassword($this->passwordHasher->hashPassword($user, 'user'));
            $manager->persist($user);
        }

        $manager->flush(); // je flush les données
    }
}
