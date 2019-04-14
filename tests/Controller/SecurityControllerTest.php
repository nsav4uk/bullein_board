<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest
 * @package App\Tests\Controller
 */
class SecurityControllerTest extends WebTestCase
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

    public function testRegistration(): void
    {
        $crawler = $this->client->request('GET', '/registration');
        $form = $crawler->selectButton('registration[submit]')->form();

        $form['registration[email]'] = 'tester@test.com';
        $form['registration[password][first]'] = 'qwerty';
        $form['registration[password][second]'] = 'qwerty';

        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('login')->form();

        $form['email'] = 'tester@test.com';
        $form['password'] = 'qwerty';

        $this->client->submit($form);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}