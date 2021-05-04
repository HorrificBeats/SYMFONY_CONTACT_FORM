<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Current user
            $user = $this->getUser();
            //Form data
            $data = $form->getData();

            //ATTACHMENT LOGIC
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['attachment']->getData();
            
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($destination, $newFilename);

                //dd($newFilename);

                $attachmentPath = $this->getParameter('kernel.project_dir') . '/public/uploads/'. $newFilename;
            }
            $email = (new Email())
                ->from($user->getEmail())
                ->to('contact@website.com')
                ->subject('Message from ' . $data['firstName'] . ' !')
                ->text($data['message'])
                ->attachFromPath($attachmentPath, 'Resume_'.$data['firstName'].'_'.$data['lastName']);
                
            
            $this->addFlash('success', 'Application sent! Talk to you soon!');
            $mailer->send($email);
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
