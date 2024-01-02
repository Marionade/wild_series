<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
use App\Entity\User;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ActorRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\Security\Core\User\UserInterface;



#[Route('/program')]
class ProgramController extends AbstractController
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger, ProgramRepository $programRepository){
        $this->slugger = $slugger;
        $this->programRepository = $programRepository;
    }

    #[Route('/', name: 'app_program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();    

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'app_program_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MailerInterface $mailer, ProgramRepository $programRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            $this->addFlash('success', 'The new program has been created');

            $email = (new Email())
            ->from('your_email@example.com')
            ->to('your_email@example.com')
            ->subject('Une nouvelle série vient d être publiée')
            ->html('<p>Une nouvelle série vient d être publiée sur Wild Séries !</p>');

        $mailer->send($email);

        $this->getParameter('mailer_from');

            return $this->redirectToRoute('app_program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_program_show', methods: ['GET'])]
    public function show(Program $program, string $slug, SluggerInterface $slugger, ProgramDuration $programDuration): Response
    {
        $slug = $slugger->slug($program->getTitle());
        $program->setSlug($slug); 
        $duration = $programDuration->calculate($program);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'duration'=> $duration,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, string $slug, SluggerInterface $slugger): Response
    {
        $program = $this->programRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $entityManager->flush();
            $this->addFlash('success', 'le programme est mis à jour');

            return $this->redirectToRoute('app_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_program_delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();

            return $this->addFlash('danger', 'Le programme a été supprimé');
        }

        return $this->redirectToRoute('app_program_index', [], Response::HTTP_SEE_OTHER);
    }


#[Route('/{slug}/season/{season}', name: 'season_show')]
public function showSeason(string $slug, Program $program, SluggerInterface $slugger, Season $season): Response
{   
    $slug = $slugger->slug($program->getTitle());
    $program->setSlug($slug);
    return $this->render('program/season_show.html.twig', [
        'program' => $program,
        'season' => $season,
    ]);
}

#[Route('/{slug}/season/{season}/episode/{episode}', name: 'episode_show')]
public function showEpisode(Program $program, Season $season, Episode $episode, SluggerInterface $slugger, Request $request, UserInterface $user, EntityManagerInterface $entityManager): Response
{
    $slug = $slugger->slug($program->getTitle());
    $program->setSlug($slug);
    $episode->setSlug($slug);

    $comment = new Comment();
    $comment->setCreatedAt(new \DateTimeImmutable());
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $comment->setEpisode($episode);
        $comment->setUser($user);
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('episode_show',[
            'slug' => $program->getSlug(),
            'season' => $season->getId(),
            'episode' => $episode->getId()
        ]);
    }
            $comments = $entityManager->getRepository(Comment::class)->findBy(['episode' => $episode]);

    return $this->render('program/episode_show.html.twig', [
        'program' => $program,
        'season' => $season,
        'episode' => $episode,
        'form' => $form,
        'comments' => $comments,
        ]);    
    }

}

