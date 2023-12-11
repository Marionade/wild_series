<?php
namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface 
{

    public function load(ObjectManager $manager):void{
        $faker = Factory::create('fr_FR');

        foreach(ProgramFixtures::getTitles() as $program){
            for($i = 1; $i < 11; $i++) {
                $actor = new Actor();
                $actor->setName($faker->name());
                $actor->setProgram($this->getReference('program_' . $program));
                $this->addReference('program_' . $program . 'actor_' . $i, $actor);

                $manager->persist($actor);
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
