<?php

namespace App\Controller;

use DateTime;
use App\DTO\ContactDTO;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Création d'un nouvel e-mail
                $email = (new TemplatedEmail())
                    ->from($data->email)
                    ->to($data->service)
                    ->subject($data->subject)
                    ->text($data->message)
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context(['data' => $data, 'expiration_date' => new DateTime('+7 days')]);

                $mailer->send($email);

                $this->addFlash('success', "Votre émail a bien été envoyé");
                return $this->redirectToRoute('app_recipe');
            } catch (\Exception $e) {
                // En cas d'échec de l'envoi de l'e-mail, affichez un message d'erreur
                $this->addFlash('danger', "Une erreur est survenue lors de l'envoi de l'e-mail." );

                // Journalisation de l'erreur
                $this->logger->error('Failed to send email: ' . $e->getMessage());
                return $this->redirectToRoute('app_contact');
            }
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
