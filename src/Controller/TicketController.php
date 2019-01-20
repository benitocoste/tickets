<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\Ticket;

class TicketController extends AbstractController
{
    /**
     * @Route("/", name="ticket_liste")
     */
    public function liste(){
        //le manager
        $repositoryTicket = $this->getDoctrine()->getRepository(Ticket::class);

        $tickets = $repositoryTicket->findAll();
        
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }
    /**
     * @Route("/ticket/creer", name="ticket_creer")
     */
    public function creer(Request $request){
         //on récupere le manager

         $ticket = new Ticket();
         $ticket->setObjet('Premier problème');
         $ticket->setMessage("J'ai un gros problème");
         $datetimedumoment  = new \DateTime('now');
 
         $ticket->setDatecreation($datetimedumoment);
         $ticket->setEtat(1);
        
         //on créé le form
         $form = $this->createFormBuilder($ticket)
            ->add('Objet', TextType::class, ['attr' => ['class' => 'form-input'],])
            ->add('Message', TextAreaType::class, ['attr'=> ['class'=> 'form-input'],])
            ->add('datecreation', DateType::class,['attr'=> ['class'=> 'form-input'],])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Créer ticket',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->getForm();

        //on manipule la request au cas ou 
        $form->handleRequest($request);

        //si on a soumis le form
        if ($form->isSubmitted() && $form->isValid()){
            //on récupère les données
            $ticket = $form->getData();
            //on récupère em
            $entityManager = $this->getDoctrine()->getManager();
            //on persist
            $entityManager->persist($ticket);
            //on termine la transaction
            $entityManager->flush();
            //et on redirige vers la liste
            return $this->redirectToRoute('ticket_liste');
        }
         return $this->render('ticket/creer.html.twig', [
             'form' => $form->createView(),
         ]);
    }
    /**
     * @Route("/ticket/supprimer/{id}", defaults={"id"= 0}, name="ticket_supprimer")
     */
    public function supprimer($id){
        //on va chercher notre ticket
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = new Ticket();
        $ticket = $repository->findOneBy(
            ['id' => $id]
        );

        //on le supprimer
        $entityManager->remove($ticket);
        //on ferme la transaction
        $entityManager->flush();
        //on redirige vers la liste
        return $this->redirectToRoute('ticket_liste');
        

    }
    /**
     * @Route("/ticket/modifier/{id}", name="ticket_modifier")
     */
    public function modifier(Request $request, $id){
        //on récupère l'entité 
        $repository = $this->getDoctrine()->getRepository(Ticket::class);
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = new Ticket();
        $ticket = $repository->findOneBy(
            ['id' => $id]
        );
        //on créé le form 
        
        $form = $this->createFormBuilder($ticket)
        ->add('Objet', TextType::class, ['attr' => ['class' => 'form-input'],])
        ->add('Message', TextAreaType::class, ['attr'=> ['class'=> 'form-input'],])
        ->add('datecreation', DateType::class,['attr'=> ['class'=> 'form-input'],])
        ->add('enregistrer', SubmitType::class, [
            'label' => 'Modifier',
            'attr' => ['class' => 'btn btn-primary'],
        ])
        ->getForm();
        //on manipule la request au cas ou 
        $form->handleRequest($request);

        //si on a soumis le form
        if ($form->isSubmitted() && $form->isValid()){
            //on récupère les données
            $ticket = $form->getData();
            //on récupère em
            $entityManager = $this->getDoctrine()->getManager();
            //on persist
            $entityManager->persist($ticket);
            //on termine la transaction
            $entityManager->flush();
            //et on redirige vers la liste
            return $this->redirectToRoute('ticket_liste');
        }

        return $this->render('ticket/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
