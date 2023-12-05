<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
use App\entity\Episode;
use App\entity\Program;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

public function load(ObjectManager $manager): void
{
        $faker = Factory::create('fr_FR');

        /**
        * L'objet $faker que tu récupère est l'outil qui va te permettre 
        * de te générer toutes les données que tu souhaites
        */
        foreach(ProgramFixtures::getTitles() as $program){

        for($i = 1; $i < 7; $i++) {
            $season = new Season();
            //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
            $season->setNumber($i);
            $season->setYear($faker->year());
            $season->setDescription($faker->paragraphs(3, true));

            $season->setProgram($this->getReference('program_' . $program));

            $this->addReference('program_' . $program . 'season_' . $i, $season);

            $manager->persist($season);
        }
    }

        $manager->flush();
    }
    public function getDependencies() : array
    {
        return [
          ProgramFixtures::class,
        ];
    }
}

/*    private const SEASON = [
        [
            'program' => 'Breaking Bad',
            'number'=>'1',
            'year'=>'2008',
            'description' => 'Walter « Walt » White, père de famille de 50 ans, est professeur de chimie dans un lycée du Nouveau-Mexique. Son épouse Skyler est enceinte et son fils de 16 ans, Walter Jr., est handicapé. Son quotidien déjà morose devient noir lorsqu il apprend qu il est atteint d un incurable cancer des poumons. Les médecins ne lui donnent pas plus de deux ans à vivre. Hank, son beau-frère et agent à la DEA, lui parle des énormes sommes d argent brassées par le marché de la drogue. Afin d économiser suffisamment d argent pour sa famille lorsqu il ne sera plus de ce monde, Walter décide de s associer avec Jesse Pinkman, un de ses anciens élèves devenu junkie et accessoirement dealer. Grâce à ses connaissances poussées en chimie, Walter produit de la méthamphétamine à la limite de la perfection tandis que Jesse se charge de la distribution du « Blue Sky », surnom donné au produit en raison de sa pureté et de sa couleur bleutée. Le duo improvisé met alors en place un labo itinérant dans un vieux camping-car et commence à « cuisiner » dans le secret au milieu d un désert d\'Albuquerque.
            Les deux hommes seront vite dépassés par leur double vie et devront faire face à la violente réalité du milieu de la drogue qui les plongera dans une longue descente aux enfers.',
        ],
        [
            'program' => 'The Good Place',
            'number'=>'1',
            'year'=>'2016',
            'description' => 'Eleanor Shellstrop, une femme qui a mené une vie immorale, se retrouve par erreur au Bon Endroit, une utopie après la mort. Elle doit apprendre à devenir une personne meilleure pour y rester.         
             Eleanor est accueillie par Michael, l\'ange gardien du Bon Endroit. Michael lui explique que le Bon Endroit est un endroit où les gens sont récompensés pour leur bon comportement dans la vie. Eleanor est surprise d\'être là, car elle a toujours été une mauvaise personne. Elle décide de se faire passer pour une autre personne, et elle commence à apprendre à être une meilleure personne.
             Cependant, Eleanor n\est pas la seule personne qui n\appartient pas au Bon Endroit. Il y a aussi Chidi Anagonye, un philosophe qui est mort avant de pouvoir mettre en pratique ses principes moraux. Il y a aussi Tahani Al-Jamil, une philanthrope qui a toujours été obsédée par l\apparence. Les trois personnages doivent apprendre à vivre ensemble et à devenir de meilleures personnes.',
        ],
        [
            'program' => 'Stranger Things',
            'number'=>'1',
            'year'=>'2016',
            'description' => 'En 1983, dans la petite ville de Hawkins, Indiana, un jeune garçon disparaît mystérieusement. Son ami et sa sœur, accompagnés d\'un groupe d\'amis, se lancent dans une enquête qui les mènera à découvrir un secret terrifiant.
            Will Byers, un jeune garçon de 12 ans, disparaît un soir alors qu\'il joue dans la forêt avec ses amis. Sa mère, Joyce, est désespérée et fait appel à la police. Le chef de la police, Jim Hopper, mène l\'enquête avec l\'aide de Jonathan, le frère aîné de Will.
            Pendant ce temps, Mike, Dustin et Lucas, les amis de Will, continuent de chercher leur ami. Ils découvrent que Will est entré dans un autre monde, appelé le Monde à l\'envers. Ils doivent trouver un moyen de le ramener à la maison
            Stranger Things est une série fantastique qui mêle suspense, horreur et amitié. Elle a été acclamée par la critique et le public, et a remporté de nombreux prix.',
        ],
        [
            'program' => 'American Horror Story',
            'number'=>'1',
            'year'=>'2011',
            'description' => 'American Horror Story est une anthologie d’horreur télévisée créée par Ryan Murphy et Brad Falchuk. Chaque saison est conçue comme une mini-série autonome, suivant une histoire différente avec des personnages, des décors et parfois des membres de la distribution qui se chevauchent.
            La première saison, intitulée Murder House, se déroule dans une maison hantée à Los Angeles. La famille Harmon, composée de Ben, Vivien, Violet et Michael, emménage dans la maison. Ils découvrent rapidement que la maison est hantée par les fantômes de ses anciens propriétaires.
            La saison 1 a été acclamée par la critique et le public. Elle a remporté de nombreux prix, dont le Golden Globe de la meilleure série télévisée - Drame.',
        ],
        [
            'program' => 'Lupin',
            'number'=>'1',
            'year'=>'2021',
            'description' => 'Lupin est une série dramatique policière française créée par George Kay et François Uzan. La série est une adaptation du gentleman cambrioleur fictif Arsène Lupin des romans de Maurice Leblanc.
            Assane Diop est un jeune homme qui a été injustement accusé du meurtre de son père, un célèbre voleur de bijoux. Son père était un admirateur d\'Ars',
        ]  
    ];

    public function load(ObjectManager $manager): void
    {
        $programs = $manager->getRepository(\App\Entity\Program::class)->findAll();

        $seasons = self::SEASON;

        for ($i = 0; $i < count($seasons); $i++ ){
            $season = new Season();
            $season->setNumber($seasons[$i]['number']);
            $season->setYear($seasons[$i]['year']);
            $season->setDescription($seasons[$i]['description']);

            $season->setProgram($this->getReference('program_' . $seasons[$i]['program']));
            $manager->persist($season);

            $this->addReference('program_' . $seasons[$i]['program'] . ' season_'. $seasons[$i]['number'], $season);
              }
*/