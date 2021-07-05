<?php

namespace App\Tests\FunctionalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostDetailTest extends WebTestCase
{
    public function testCommentSubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $link = $crawler->selectLink('Read More →')->link();
        $pageDetailCrawler = $client->click($link);

        $this->assertResponseIsSuccessful();

        $form = $pageDetailCrawler->selectButton('Submit')->form();
        $form['comment[author]'] = 'Teebblog';
        $form['comment[email]'] = 'Teebblog@example.com';
        $form['comment[message]'] = '你好，世界！';
        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Teebblog', $client->getResponse()->getContent());
        $this->assertStringContainsString('你好，世界！', $client->getResponse()->getContent());
    }
}
