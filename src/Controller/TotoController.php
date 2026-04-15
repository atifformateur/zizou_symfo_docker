<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class TotoController extends AbstractController
{
    #[Route('/first', name:'app_first', methods: ['GET'])]
    public function first(): Response
    {
        //j'instancie player
        $newPlayer = new Player();
        //jutilise les setters pour remplir mon instance
        $newPlayer
            ->setNom('toto')
            ->setNumber(10)
            ->setUsername('totoname');
       
        dd($newPlayer);
        return $this->render('/toto/first.html.twig');
    }

    #[Route('/second', name:'app_second', methods:['GET'])]
    public function second(): Response
    {
        $variable = 'le contenu de ma variable';
        
        return $this->render('/toto/second.html.twig', [
            'variable' => $variable
        ]);
    }

    #[Route('/third', name:'app_third', methods: ['GET'])]
    public function third(): Response
    {
        $cars = [
            'merco',
            'porsche',
            'audi',
            'dacia',
            'lada de mikalai'
        ];

        return $this->render('toto/third.html.twig', [
            'cars'=>$cars
        ]);
    }



    // #[Route('/toto', name: 'app_toto')]
    // public function login(): Response
    // {

    //     3 page (trois methodes dans le controller)
    //     1 qui affiche directement en twig hello world
    //     2 qiu affiche le contenu d'une variable créer dans le controller
    //     3 qui affiche le contenu d'un tableau créer dans le controller 
    //     bonus faire au moin 1 dump dans le controller et 1 dump dans le twig et 1 dump die !
    //     $cars = [

    //     ];

    //     return $this->render('toto/index.html.twig', 
    //     [
    //         'cars' => $cars,
    //     ]);
    // }

}
