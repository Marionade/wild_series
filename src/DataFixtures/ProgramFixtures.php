<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Program;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface 
{
    private const PROGRAM =  [
        [
            'title'=>'Breaking Bad',
            'synopsis'=>'Les médecins ne lui donnent pas plus de deux ans à vivre. Pour réunir rapidement beaucoup d argent afin de mettre sa famille à l abri, Walter ne voit plus qu une solution, mettre ses connaissances en chimie à profit pour fabriquer et vendre du crystal meth...',
            'category'=>'category_Action',
            'country'=>'USA',
            'year'=>'2008'
        ],
        [
            'title' => 'The Good Place',
            'synopsis' => 'Une femme qui a mené une vie immorale se retrouve par erreur au Bon Endroit, une utopie après la mort. Elle doit apprendre à devenir une personne meilleure pour y rester.',
            'category' => 'category_Comédie',
            'country'=>'USA',
            'year'=>'2016'
        ],
        [
            'title' => 'Stranger Things',
            'synopsis' => 'En 1983, dans la petite ville de Hawkins, Indiana, un jeune garçon disparaît mystérieusement. Son ami et sa sœur, accompagnés d un groupe d amis, se lancent dans une enquête qui les mènera à découvrir un secret terrifiant.',
            'category' => 'category_Fantastique',
            'country'=>'USA',
            'year'=>'2016'
        ],
        [
            'title' => 'American Horror Story',
            'synopsis' => 'Une anthologie d’horreur télévisée créée par Ryan Murphy et Brad Falchuk. Chaque saison est conçue comme une mini-série autonome, suivant une histoire différente avec des personnages, des décors et parfois des membres de la distribution qui se chevauchent.',
            'category' => 'category_Horreur',
            'country'=>'USA',
            'year'=>'2011'
        ],
        [
            'title' => 'Lupin',
            'synopsis' => 'Une série dramatique policière française créée par George Kay et François Uzan. La série est une adaptation du gentleman cambrioleur fictif Arsène Lupin des romans de Maurice Leblanc.',
            'category' => 'category_Aventure',
            'country'=>'France',
            'year'=>'2021'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAM as $programData){
            $program = new Program();
            $program->setTitle($programData['title']);
            $program->setSynopsis($programData['synopsis']);
            $program->setCountry($programData['country']);
            $program->setYear($programData['year']);
            $program->setCategory($this->getReference($programData['category']));
            $this->addReference('program_' . $programData['title'] , $program);
            $manager->persist($program);
        }

        $manager->flush();
    }

    static function getTitles(): array
    {
        return array_map(fn ($arr) => $arr['title'], self::PROGRAM);
    }
    
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }

}
