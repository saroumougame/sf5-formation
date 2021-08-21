<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler= $client->request('GET', 'movie/1');

        // page is accessible
        $this->assertResponseIsSuccessful();

        // h1 equals
        $this->assertSelectorTextContains('h1', 'Memento');

        $reviewSelector = '.row.p-sm-5'; // Expect 1 of this
        $this->assertEquals(1, $crawler->filter($reviewSelector)->count());
    }
}
