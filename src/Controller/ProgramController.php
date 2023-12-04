<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

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

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);
    
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/season/show/{id<^[0-9]+$>}', name: 'show_season')]
    public function showSeason(int $programId, int $seasonId) : response {
        while ($seasonId === $programId) {
            $season = $seasonID->findOneBy(['id' => $id]);
        }
    
        if (!$season) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found.'
            );
        }
        return $this->render('program/season/show.html.twig', [
            'season' => $season,
        ]);


    }
}