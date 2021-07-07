<?php

namespace App\Tests\FunctionalTest;

use Symfony\Component\Panther\PantherTestCase;

class CommentTest extends PantherTestCase
{
    public function testReplyComment(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Read More →')->link();
        $pageDetailCrawler = $client->click($link);

        $form = $pageDetailCrawler->selectButton('Submit')->form();
        $form['comment[author]'] = 'Teebblog';
        $form['comment[email]'] = 'Teebblog@example.com';
        $form['comment[message]'] = '你好，世界！';
        $client->submit($form);

//        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.media-body', 'Teebblog');
        $this->assertSelectorTextContains('.media-body', '你好，世界！');

        $client->executeScript('document.querySelector(".js-reply-comment-btn").click()');
        $newPageDetailCrawler = $client->waitFor('div.reply-comment-card');

        $this->assertSelectorTextContains('div.reply-comment-card', '回复评论');

        $replyCommentDivCrawler = $newPageDetailCrawler->filter('div.reply-comment-card');
        $replyform = $replyCommentDivCrawler->selectButton('Submit')->form();
        $replyform['comment[author]'] = 'Teebblog2';
        $replyform['comment[email]'] = 'Teebblog2@example.com';
        $replyform['comment[message]'] = '测试回复评论';
        $client->submit($replyform);

        $this->assertSelectorTextContains('.media-body', 'Teebblog2');
        $this->assertSelectorTextContains('.media-body', '测试回复评论');

        $client->takeScreenshot('screen.png');

    }
}
