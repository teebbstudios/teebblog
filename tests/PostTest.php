<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Post;

class PostTest extends TestCase
{
    public function testPostTitle(): void
    {
        $post = new Post();

        $post->setTitle("这是一个标题");

        $this->assertSame("这是一个标题", $post->getTitle());

    }
}
