<?php

namespace App\Test\Controller;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProgramControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProgramRepository $repository;
    private string $path = '/program/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Program::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Program index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'program[title]' => 'Testing',
            'program[synopsis]' => 'Testing',
            'program[poster]' => 'Testing',
            'program[country]' => 'Testing',
            'program[year]' => 'Testing',
            'program[category]' => 'Testing',
        ]);

        self::assertResponseRedirects('/program/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Program();
        $fixture->setTitle('My Title');
        $fixture->setSynopsis('My Title');
        $fixture->setPoster('My Title');
        $fixture->setCountry('My Title');
        $fixture->setYear('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Program');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Program();
        $fixture->setTitle('My Title');
        $fixture->setSynopsis('My Title');
        $fixture->setPoster('My Title');
        $fixture->setCountry('My Title');
        $fixture->setYear('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'program[title]' => 'Something New',
            'program[synopsis]' => 'Something New',
            'program[poster]' => 'Something New',
            'program[country]' => 'Something New',
            'program[year]' => 'Something New',
            'program[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/program/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getSynopsis());
        self::assertSame('Something New', $fixture[0]->getPoster());
        self::assertSame('Something New', $fixture[0]->getCountry());
        self::assertSame('Something New', $fixture[0]->getYear());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Program();
        $fixture->setTitle('My Title');
        $fixture->setSynopsis('My Title');
        $fixture->setPoster('My Title');
        $fixture->setCountry('My Title');
        $fixture->setYear('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/program/');
    }
}
