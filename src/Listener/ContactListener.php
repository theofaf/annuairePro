<?php

namespace App\Listener;

use App\Entity\Contact;
use App\Entity\Historique;
use App\Entity\Message;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preRemove, priority: 500, connection: 'default')]
class ContactListener
{
    public const CHAMPS_CONTACT = [
        'nom',
        'prenom',
        'telephone',
        'email',
    ];

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $contact = $args->getObject();

        if (!$contact instanceof Contact) {
            return;
        }

        $contactBdd = $entityManager->getRepository(Contact::class)->find($contact->getId());

        $historique = (new Historique())
            ->setDate(new DateTime())
            ->setContact($contactBdd)
            ->setAction('creation')
            ->setAncienneValeur(null)
            ->setNouvelleValeur($contact->__toString())
        ;

        $entityManager->persist($historique);
        $entityManager->flush();
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
//        $contact = $args->getObject();
//        if (!$contact instanceof Contact) {
//            return;
//        }
//
//        $ancienneValeur = '';
//        foreach (self::CHAMPS_CONTACT as $champ) {
//            $ancienneValeur .= $champ . ":" . $args->getOldValue($champ) . " ";
//        }
//
//        $historique = (new Historique())
//            ->setDate(new DateTime())
//            ->setContact($contact)
//            ->setAction('mis à jour')
//            ->setAncienneValeur($ancienneValeur)
//            ->setNouvelleValeur($contact->__tostring())
//        ;
//
//        $entityManager = $args->getObjectManager();
//        $entityManager->persist($historique);
//        $entityManager->flush();
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
//        $entityManager = $args->getObjectManager();
//        $contact = $args->getObject();
//
//        if (!$contact instanceof Contact) {
//            return;
//        }
//
//        $messages = $entityManager->getRepository(Message::class)->findBy([
//            'contact' => $contact->getId(),
//        ]);
//
//        foreach ($messages as $message) {
//            $entityManager->remove($message);
//        }
//
//        $historique = (new Historique())
//            ->setDate(new DateTime())
//            ->setContact($contact)
//            ->setAction('suppression')
//            ->setAncienneValeur(null)
//            ->setNouvelleValeur(null)
//        ;
//
//        $entityManager->persist($historique);
//        $entityManager->flush();
    }
}