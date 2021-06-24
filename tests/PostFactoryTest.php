<?php

namespace App\Tests;

use App\Entity\Post;
use PHPUnit\Framework\TestCase;
use App\Factory\PostFactory;

class PostFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $factory = new PostFactory();
        $post = $factory->create("这是一个标题", "这是正文", "这是摘要");

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame('draft', $post->getStatus());

        $post2 = $factory->create('这是第二个文章标题', '<h1>这是第二个文章正文</h1>');
        $this->assertSame('这是第二个文章正文', $post2->getSummary());
    }
}
