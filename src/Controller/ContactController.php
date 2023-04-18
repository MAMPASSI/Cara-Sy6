<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash('success','votre message a bien été envoyer');

        }
        return $this->render('contact/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
