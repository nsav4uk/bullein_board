<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IndexControllerTest
 * @package App\Tests
 */
class IndexControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(3, $crawler->filter('li'));
        $this->assertEquals('Purchase', $crawler->filter('li')->text());

        $crawler = $client->request('GET', '/ru/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(3, $crawler->filter('li'));
        $this->assertEquals('Покупка', $crawler->filter('li')->text());
    }
}