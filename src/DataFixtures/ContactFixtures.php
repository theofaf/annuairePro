<?php

namespace App\DataFixtures;

use App\Entity\Collaborateur;
use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $contactsData = [
            [
                'nom' => 'melin',
                'prenom' => 'lucas',
                'email' => 'lucas@mail.com',
                'telephone' => '0612324569',
            ],
            [
                'nom' => 'pinot',
                'prenom' => 'edouard',
                'email' => 'edouard@mail.fr',
                'telephone' => '0612387569',
            ],
            [
                'nom' => 'dupont',
                'prenom' => 'margot',
                'email' => 'margot@mail.fr',
                'telephone' => '0612324987',
            ],
            [
                'nom' => 'direz',
                'prenom' => 'silvie',
                'email' => 'silvie@mail.fr',
                'telephone' => '0614569569',
            ],
        ];

        foreach ($contactsData as $contactData) {
            $contact = (new Contact())
                ->setNom($contactData['nom'])
                ->setPrenom($contactData['prenom'])
                ->setEmail($contactData['email'])
                ->setTelephone($contactData['telephone'])
                ->setEstSupprime(false)
            ;

            $manager->persist($contact);
        }

        $manager->flush();
    }
}