<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team')]
final class TeamController extends AbstractController
{

    // une team
    #[Route('/show/{id}', name: "team_show", methods: ['GET'])]
    public function show(Team $team)
    {


        return $this->render('/team/show.html.twig', [
            'team' => $team
        ]);
    }

    // add team
    #[Route('/new', name: "team_new", methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em )
    {

        $newTeam = new Team();
        $formTeam = $this->createForm(TeamType::class, $newTeam);

        $formTeam->handleRequest($request);
        

        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->persist($newTeam);
            $em->flush();
            
            // dd($request);
            return $this->redirectToRoute('team_index');
        }

       return $this->render('/team/new.html.twig',[
            'formTeam' => $formTeam
       ]);

    }

    //update 
    #[Route('/update/{id}', name: "team_update", methods: ['GET', 'POST'])]
    public function update(Team $team, Request $request, EntityManagerInterface $em)
    {
        $formTeam = $this->createForm(TeamType::class, $team);        
        $formTeam->handleRequest($request);

        if($formTeam->isSubmitted() && $formTeam->isValid()){
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/update.html.twig', [
            'formTeam' => $formTeam
        ]);

    }

    // delete team 
    #[Route('/delete/{id}', name: "team_delete", methods: ['POST'])]
    public function delete(Team $team, Request $request, EntityManagerInterface $em)
    {
        if($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))){
            $em->remove($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }
    }

    #[Route('/', name:'team_index', methods:['GET'])]
    public function index(TeamRepository $teamRepository)
    {
        
       $teams = $teamRepository->findAllTeams();


       return $this->render('team/index.html.twig', [
        'teams' => $teams
       ]);
    }
}
