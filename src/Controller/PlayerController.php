<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player')]
final class PlayerController extends AbstractController
{
    //endpoint affiche tous les players
    #[Route('/', name:'player_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository, Request $request): Response
    {
        //get du parametre 'q' -> si pas de q on remplace par une string vide
        $q = $request->query->get('q', '');
        //on verifie si q est remplis
        if($q) {
            //si il est remplis on fait appel a notre methode search pour get les elements correspondants
            $players = $playerRepository->search($q);
        } else {
            //sinon on recupere tous les elements players
            $players = $playerRepository->findAll();
        }

        return $this->render('player/index.html.twig', [
            'players' => $players,
            'q' => $q
        ]);
    }

    #[Route('/show/{id}', name: 'player_show', methods: ['GET'])]
    public function show(Player $player): Response
    {
        return $this->render('player/show.html.twig', [
            'player' => $player
        ]);
    }

    //endpoint add d'un player
    #[Route('/new', name: 'player_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newPlayer = new Player(); 

        $formPlayer = $this->createForm(PlayerType::class, $newPlayer);
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid()) {

            $em->persist($newPlayer);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }
        
        return $this->render('player/new.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }

    #[Route('/update/{id}', name: 'player_update', methods: ['GET', 'POST'])]
    public function update(
            Player $player, 
            Request $request, 
            EntityManagerInterface $em
        ){
        //set les modifs
        $formPlayer = $this->createForm(PlayerType::class, $player);
        $formPlayer->handleRequest($request);

        if($formPlayer->isSubmitted() && $formPlayer->isValid()){
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }
        return $this->render('/player/update.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }

    #[Route('/delete/{id}', name: 'player_delete', methods: ['POST'])]
    public function delete(Player $player, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            $em->remove($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }
    }

    #[Route('/async', name: 'player_async_search', methods: ['GET'])]
    public function async(): Response
    {
        return $this->render('player/search.html.twig');
    }

    #[Route('/async/search', name: 'player_async', methods: ['GET'])]
    public function asyncSearch(Request $request, PlayerRepository $playerRepository, ): JsonResponse
    {
        
        $q = $request->query->get('q', '');

        $players = $q ? $playerRepository->search($q) : $playerRepository->findAll();

        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'nom' => $p->getNom(),
            'username' => $p->getUsername(),
            'number' => $p->getNumber(),
        ], $players);

        return $this->json($data);
    }

    //installation de uxturbo composer require symfony/ux-turbo
    #[Route('/search/turbo', name: "player_turbo", methods: ['GET'])]
    public function searchTurbo(Request $request, PlayerRepository $playerRepository): Response
    {
        $q = $request->query->get('q');

        $players = $q ? $playerRepository->search($q) : $playerRepository->findAll();

        return $this->render('player/turbo.html.twig', [
            'q' => $q,
            'players' => $players
        ]);
    }
}
