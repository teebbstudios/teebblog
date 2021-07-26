<?php

namespace App\Tests\UnitTest;

use App\Entity\Post;
use PHPUnit\Framework\TestCase;
use App\Factory\PostFactory;

class PostFactoryTest extends TestCase
{
    public function testFactory(): void
    {
//        $factory = new PostFactory();
        $factory = $this->createMock(PostFactory::class);

        $postObj = new Post();
        $postObj->setTitle('这是一个标题');
        $postObj->setSummary("这是摘要");
        $postObj->setBody('这是正文');
        $postObj->setStatus(['draft']);

//        $factory->expects($this->once())->method('create')
//            ->with("这是一个标题", "这是正文", "这是摘要")
//            ->willReturn($postObj);
        $postObj2 = new Post();
        $postObj2->setTitle('这是第二个文章标题');
        $postObj2->setBody('<h1>这是第二个文章正文</h1>');
        $postObj2->setSummary('这是第二个文章正文');
        $postObj2->setStatus(['draft']);

        $factory->expects($this->exactly(2))->method('create')
            ->withConsecutive(["这是一个标题", "这是正文", "这是摘要"], ['这是第二个文章标题', '<h1>这是第二个文章正文</h1>'])
            ->willReturn($postObj, $postObj2);

        $post = $factory->create("这是一个标题", "这是正文", "这是摘要");

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame($postObj, $post);
        $this->assertArrayHasKey('draft', $post->getStatus());

        $post2 = $factory->create('这是第二个文章标题', '<h1>这是第二个文章正文</h1>');
        $this->assertSame($postObj2, $post2);
        $this->assertSame('这是第二个文章正文', $post2->getSummary());
    }
}
