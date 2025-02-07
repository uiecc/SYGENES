<?php

namespace App\Tests\Controller;

use App\Entity\University;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UniversityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $universityRepository;
    private string $path = '/university/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->universityRepository = $this->manager->getRepository(University::class);

        foreach ($this->universityRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('University index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'university[name]' => 'Testing',
            'university[code]' => 'Testing',
            'university[address]' => 'Testing',
            'university[description]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->universityRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new University();
        $fixture->setName('My Title');
        $fixture->setCode('My Title');
        $fixture->setAddress('My Title');
        $fixture->setDescription('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('University');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new University();
        $fixture->setName('Value');
        $fixture->setCode('Value');
        $fixture->setAddress('Value');
        $fixture->setDescription('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'university[name]' => 'Something New',
            'university[code]' => 'Something New',
            'university[address]' => 'Something New',
            'university[description]' => 'Something New',
        ]);

        self::assertResponseRedirects('/university/');

        $fixture = $this->universityRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getAddress());
        self::assertSame('Something New', $fixture[0]->getDescription());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new University();
        $fixture->setName('Value');
        $fixture->setCode('Value');
        $fixture->setAddress('Value');
        $fixture->setDescription('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/university/');
        self::assertSame(0, $this->universityRepository->count([]));
    }
}
