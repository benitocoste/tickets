<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Ticket;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index()
    {
        //on récupere le manager
        $entityManager = $this->getDoctrine()->getManager();

        $ticket = new Ticket();
        $ticket->setObjet('Premier problème');
        $ticket->setMessage("J'ai un gros problème");
        $datetimedumoment  = new \DateTime('now');
        dump($datetimedumoment);

        $ticket->setDatecreation($datetimedumoment);
        $ticket->setEtat(1);
        //on le met dans la base
        $entityManager->persist($ticket);


        //on lance la transaction
        $entityManager->flush();


        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
    /**
     * @Route("/ticket/liste", name="ticket_liste")
     */
    public function liste(){
        //le manager
        $repositoryTicket = $this->getDoctrine()->getRepository(Ticket::class);

        $tickets = $repositoryTicket->findAll();
        
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }
}
