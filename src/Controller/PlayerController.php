<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PlayerController extends AbstractController
{
    //endpoint affiche tous les players
    #[Route('/player', name: 'player_index', methods: ['GET'])]
    public function index(): Response
    {
        dd('all players');
        return $this->render('player/index.html.twig', []);
    }

    //endpoint add d'un player
    #[Route('/player/new', name: 'player_new', methods:['GET'])]
    public function new()
    {
        //creation du form
       return $this->render('player/new.html.twig');
    }

    #[Route('/player/add', name: 'player_add', methods:[ 'POST'])]
    public function addEnDb(Request $request)
    {
        //get les infos dans la request
        $nom = $request->request->get('nom');
        $username = $request->request->get('username');
        $number = $request->request->get('number');

        //creation d'une instance de player
        $newPlayer = new Player();

        //je remplis mon instance avec les données du form
        $newPlayer
            ->setNom($nom)
            ->setUsername($username)
            ->setNumber($number);

        dd($newPlayer, $number, $username, $nom);
    }
}
