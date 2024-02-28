<?php

namespace App\DataFixtures;

use App\Entity\Collaborateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CollaborateursFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $utilisateursData = [
            [
                'nom' => 'Dujardin',
                'prenom' => 'Jean',
                'email' => 'jean@mail.com',
                'role' => 'ROLE_USER',
            ],
            [
                'nom' => 'Lamy',
                'prenom' => 'Alexandra',
                'email' => 'alex@mail.fr',
                'role' => 'ROLE_ADMIN',
            ],
        ];

        foreach ($utilisateursData as $utilisateurData) {
            $collaborateur = (new Collaborateur());
            $collaborateur->setNom($utilisateurData['nom'])
                ->setPrenom($utilisateurData['prenom'])
                ->setEmail($utilisateurData['email'])
                ->setRoles([$utilisateurData['role']])
                ->setPassword($this->passwordHasher->hashPassword($collaborateur, 'azerty123*'))
            ;

            $manager->persist($collaborateur);
        }

        $manager->flush();
    }
}