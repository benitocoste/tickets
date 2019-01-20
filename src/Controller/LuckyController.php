<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky", name="lucky")
     */
    public function index()
    {
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
        ]);
    }
    /**
    * @Route("/lucky/number", name= "lucky_number")
    */
    public function number(){
        $number = random_int(0,100);
        /*return new Response(
            ' coucou' .$number.'coucou'
        );
        */
        return $this->render('lucky/index.html.twig', [
            'number' => $number,
        ]);
         
    }
}
