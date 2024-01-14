<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use App\Repository\ProgramRepository;

#[AsLiveComponent()]
final class ProgramSearch
{ 
    private $programRepository;

        public function __construct(ProgramRepository $programRepository)
        {
            $this->programRepository = $programRepository;
        }
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function getPrograms(): array
    {
       
        return $this->programRepository->findLikeName($this->query);
    }
}
