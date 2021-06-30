<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexPageTest extends WebTestCase
{
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Teebblog List');

        $this->assertPageTitleSame('Teebblog');
    }
}
