<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IndexControllerTest
 * @package App\Tests
 */
class IndexControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->followRedirects();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->em->rollback();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertCount(3, $crawler->filter('.sidebar li'));
        $this->assertEquals('Purchase', $crawler->filter('.sidebar li')->text());

        $crawler = $this->client->request('GET', '/ru/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertCount(3, $crawler->filter('.sidebar li'));
        $this->assertEquals('Покупка', $crawler->filter('.sidebar li')->text());
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', '/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Please sign in")')->count());
    }
}