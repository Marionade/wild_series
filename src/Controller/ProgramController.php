<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig',
            [
            'programs' => $programs,
            'website' => 'Wild Series'
            ]
        );
    }

    #[Route('/program/{id}/', name: 'show')]
    public function show(Program $program): Response
    {
      return $this->render('program/show.html.twig', ['program'=>$program]);
      
     }  
   


 /*    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        //$program = $programRepository->find($id);
        // same as $program = $programRepository->find($id);
    
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
      }  */
 

    #[Route('/program/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(
        #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
        #[MapEntity(mapping: ['seasonId'=> 'id'])] Season $season,): response 
        {            
            return $this->render('program/season_show.html.twig', [
            'program'=>$program,
            'season' => $season,
        ]);    
    }

    #[Route('/program/{programId}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['programId' => 'id'])] Program $program,
        #[MapEntity(mapping: ['seasonId'=> 'id'])] Season $season,
        #[MapEntity(mapping: ['episodeId' => 'id'])]Episode $episode,): response 
        {            
            return $this->render('program/episode_show.html.twig', [
            'program'=> $program,
            'season' => $season,
            'episode'=> $episode,
        ]);    
    }
}