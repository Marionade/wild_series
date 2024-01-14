<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use App\Entity\Program;
use App\Entity\User;

#[AsLiveComponent()]
final class Watchlist
{
    use DefaultActionTrait;

    #[LiveProp]
    public Program $program;

    public function __construct (
        private ProgramRepository $programRepository,
        private Security $security,
        private EntityManagerInterface $entityManager
    ) {}

    #[LiveAction]
    public function toggle (): void
    {
        $user = $this->security->getUser();
        if ($user->getWatchlist()->contains($this->program)){
            $user->removeWatchlist($this->program);
        } else {
            $user->addWatchlist($this->program);
        }
        $this->entityManager->flush();
    }
}
