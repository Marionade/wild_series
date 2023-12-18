<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\ProgramDuration;

use Faker\Factory;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        foreach(ProgramFixtures::getTitles() as $program) {
            for($seasonNumber = 1; $seasonNumber < 7; $seasonNumber++){
                for($episodeNumber = 1; $episodeNumber < 11; $episodeNumber++){
                    $episode = new Episode();
                    $episode->setNumber($episodeNumber);
                    $episode->setTitle($faker->realText($faker->numberBetween(10, 45)));
                    $episode->setSynopsis($faker->realText());

                    $episode->setDuration(50);

                
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);


                    $episode->setSeason($this->getReference('program_' . $program . 'season_' . $seasonNumber));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }


            public function getDependencies() : array
            {
                return [
                SeasonFixtures::class,
                ];
            }
}

 /*   private const EPISODE =[
        [
            'season' => 'program_Breaking Bad season_1',
            'title'=>'Chute libre',
            'number'=>'1',
            'synopsis' => 'Jesse prend contact avec un revendeur de drogue (et ancien associé), Krazy-8, afin d\'écouler leur petit stock. Imprudent, il conduit Krazy-8 et son cousin Emilio jusqu\'au camping-car où se trouve Walt. L\'entrevue tourne mal et Walt parvient à créer un gaz toxique avant de s\'échapper du véhicule, piégeant les deux voyous. Le prologue de l\'épisode montre Walt conduire le camping-car à vive allure avec Jesse à côté de lui, jusqu\'à ce que le véhicule se retrouve dans un fossé. Walter sort du camping-car et entend au loin se rapprocher les sirènes... des pompiers à son grand soulagement.'
        ],  
        [
            'season' => 'program_Breaking Bad season_1',
            'title'=>'Le Choix',
            'number'=>'2',
            'synopsis' => 'Le lendemain, Walter et Jesse réussissent à sortir le camping-car du fossé dans le désert. Mais ils doivent se débarrasser des corps de Krazy-8 et Emilio à l\'arrière du véhicule. Leur tâche se complique quand ils découvrent avec stupéfaction que l\'un d\'entre eux a survécu. Les deux acolytes ne sont pas d\'accord quant à la marche à suivre.'
        ],  
        [
            'season'=>'program_Breaking Bad season_1',
            'title'=>'Dérapage',
            'number'=>'3',
            'synopsis' => 'Confronté à Skyler, Walt justifie sa relation avec Jesse Pinkman en prétendant qu\'il fume de la marijuana pour se sentir mieux.'
        ],
        [
            'season' => 'program_The Good Place season_1',
            'title' => 'Le Café',
            'number' => '1',
            'synopsis' => 'Eleanor, Chidi et Tahani découvrent que le Bon Endroit n\'est pas ce qu\'ils pensaient. Ils apprennent que les gens qui y vivent sont sélectionnés par Michael, leur ange gardien. Michael leur explique que le Bon Endroit est un endroit où les gens peuvent apprendre et grandir.
            Eleanor, Chidi et Tahani commencent à apprendre à vivre ensemble. Ils commencent également à apprendre à être de meilleures personnes.',
        ],
        [
            'season' => 'program_The Good Place season_1',
            'title' => 'La Machine à remonter le temps',
            'number' => '2',
            'synopsis' => 'Eleanor, Chidi et Tahani découvrent une machine à remonter le temps. Ils décident de l\'utiliser pour modifier leur passé et ainsi améliorer leur avenir.
            Les trois personnages retournent dans le passé et essaient de changer leurs vies. Cependant, ils découvrent que les choses ne sont pas aussi simples qu\'ils le pensaient.',
        ],
        [
            'season' => 'program_Stranger Things season_1',
            'title' => 'Le Club des losers',
            'number' => '1',
            'synopsis' => 'Will Byers, un jeune garçon de 12 ans, disparaît un soir alors qu\'il joue dans la forêt avec ses amis. Sa mère, Joyce, est désespérée et fait appel à la police. Le chef de la police, Jim Hopper, mène l\'enquête avec l\'aide de Jonathan, le frère aîné de Will.
            Pendant ce temps, Mike, Dustin et Lucas, les amis de Will, continuent de le chercher. Ils découvrent que Will est entré dans un autre monde, appelé le Monde à l\'envers. Ils doivent trouver un moyen de le ramener à la maison.',
        ],
        [
            'season' => 'program_Stranger Things season_1',
            'title' => 'La Chasseuse',
            'number' => '2',
            'synopsis' => 'Les amis de Will découvrent que le Monde à l\'envers est habité par des créatures dangereuses. Ils doivent trouver un moyen de se protéger.
            Pendant ce temps, Joyce découvre que Will est toujours en vie et qu\'il communique avec elle à travers les lumières de sa maison. Elle demande l\'aide de Hopper pour sauver son fils.',
        ],
        [
            'season' => 'program_Stranger Things season_1',
            'title' => 'La Baie du sang',
            'number' => '3',
            'synopsis' => 'Les amis de Will découvrent que le Monde à l\'envers est en train de se propager dans le monde réel. Ils doivent trouver un moyen d\'arrêter la créature qui le contrôle.'
        ],
        [
            'season' => 'program_American Horror Story season_1',
            'title' => 'Murder House',
            'number' => '1',
            'synopsis' => 'La famille Harmon, composée de Ben, Vivien, Violet et Michael, emménage dans une nouvelle maison à Los Angeles. Ils découvrent rapidement que la maison est hantée par les fantômes de ses anciens propriétaires.'
        ],
        [
            'season' => 'program_American Horror Story season_1',
            'title' => 'Piggy Piggy',
            'number' => '2',
            'synopsis' => 'Vivien commence à avoir des visions d\'une femme enceinte. Elle découvre que la femme est un fantôme qui a été violée et assassinée dans la maison.
            Ben commence à avoir des hallucinations d\'un homme qui lui dit de tuer sa femme.
            Violet commence à se faire harceler par un fantôme d\'un jeune garçon.',
        ],
        [
            'season' => 'program_American Horror Story season_1',
            'title' => 'Home Invasion',
            'number' => '3',
            'synopsis' => 'La famille Harmon est attaquée par un groupe de fantômes. Ben est blessé, et Vivien est kidnappée.
             Michael commence à voir des visions de la future apocalypse.',
        ],
        [
            'season' => 'program_Lupin season_1',
            'title' => 'Le Jeu de l\'ours',
            'number' => '1',
            'synopsis' => 'Assane Diop, un jeune homme qui a été injustement accusé du meurtre de son père, décide de se venger de la famille Pellegrini, responsable de sa mort. Il s\'inspire des exploits du gentleman cambrioleur Arsène Lupin pour mener sa vengeance.
            Assane commence par cambrioler le collier de Marie Antoinette, qui est exposé au musée du Louvre. Il parvient à s\'échapper, mais il est poursuivi par la police.    Assane fait la connaissance de Claire, une policière qui enquête sur le vol du collier. Il la séduit pour l\'utiliser à ses propres fins.',
        ],
        [
            'season' => 'program_Lupin season_1',
            'title' => 'La Dame dans l\'ombre',
            'number' => '2',
            'synopsis' => 'Assane tente de découvrir qui a tué son père. Il apprend que son père travaillait pour la famille Pellegrini, et qu\'il était sur le point de révéler un scandale.
            Assane rencontre Raoul, le fils de Hubert Pellegrini, qui est également enquêteur sur le vol du collier. Assane le manipule pour obtenir des informations.
            Claire commence à douter des intentions d\'Assane.',
        ],
        [
            'season' => 'program_Lupin season_1',
            'title' => 'Les Secrets de la maison',
            'number' => '3',
            'synopsis' => 'Assane découvre que son père avait une liaison avec une femme, Nicole. Il décide de la rencontrer pour en savoir plus sur son père.
            Assane apprend que Nicole est la mère de Raoul, et que son père était prêt à la quitter pour elle.
            Claire découvre que Assane est le fils de Babakar Diop, l\'homme qu\'elle recherche pour le meurtre de son père.',
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        $episodes = self::EPISODE;

        for ($i = 0; $i < count($episodes); $i++ ){
            $episode = new Episode();
            $episode->setTitle($episodes[$i]['title']);
            $episode->setNumber($episodes[$i]['number']);
            $episode->setSynopsis($episodes[$i]['synopsis']);

            $episode->setSeason($this->getReference($episodes[$i]['season']));
            $manager->persist($episode);

            $this->addReference('saison_' . $episodes[$i]['season'] . ' episode_'. $episodes[$i]['number'], $episode);
        }

        $manager->flush();
   }
     */