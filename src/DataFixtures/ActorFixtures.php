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

            for($i = 1; $i < 25; $i++) {
                $programs = ProgramFixtures::getTitles();
                $programsRandKeys = array_rand($programs, 3);
                $actor = new Actor();
                $actor->setName($faker->name());

                $manager->persist($actor);
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
