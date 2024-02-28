<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Message;
use App\Form\ContactType;
use App\Form\MessageType;
use App\Repository\ContactRepository;
use App\Service\PDFService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

#[Route('/annuaire')]
class ContactController extends AbstractController
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
    ) {
    }

    #[Route('/', name: 'accueil', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $contacts = $this->contactRepository->findBy([
            'estSupprime' => false,
        ]);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/nouveau', name: 'nouveau-contact', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'detail-contact', methods: ['GET', 'POST'])]
    public function show(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($contact->estSupprime()) {
            throw new NotFoundHttpException();
        }

        // recuperation des messages pour le contact, du plus ou moins récent
        $messages = $entityManager->getRepository(Message::class)->findBy(['contact' => $contact],  [
            'date' => 'DESC',
        ]);

        $form = $this->createForm(MessageType::class)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Message $message */
            $message = $form->getData();
            // ajout de l'heure, du collab et du contact courant
            // ajout d'une assert !blank dans entity message prop contenu
            $message
                ->setContact($contact)
                ->setDate(new DateTime())
                ->setCollaborateur($this->getUser())
            ;

            $entityManager->persist($message);
            $entityManager->flush();

            // on redirige sur la page actuel
            return $this->redirectToRoute('detail-contact', [
                'id' => $contact->getId(),
            ]);
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            'messages' => $messages,
            'form' => $form->createView(),
            'impression' => false,
        ]);
    }

    #[Route('/{id}/editer', name: 'editer-contact', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($contact->estSupprime()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    // ajout du mot 'admin' pour le pattern du control access
    #[Route('/admin/supprimer/{id}', name: 'supprimer-contact', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($contact->estSupprime()) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contact->setEstSupprime(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
    }

    // ajout du mot 'admin' pour le pattern du control access
    #[Route('/impression/{id}', name: 'imprimer-contact', methods: ['GET'])]
    public function imprimer(Request $request, Contact $contact): Response
    {
        if ($contact->estSupprime()) {
            throw new NotFoundHttpException();
        }

        $template = $this->renderView('contact/show.html.twig', [
            'contact' => $contact,
            'impression' => true,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($template);
        $dompdf->render();

        $nomFichier = 'contact.pdf';
        $output = $dompdf->output();

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename='. $nomFichier);

        file_put_contents($nomFichier, $output);

        return $response;
    }
}
