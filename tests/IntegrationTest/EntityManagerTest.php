<?php

namespace App\Tests\IntegrationTest;

use App\Factory\PostFactory;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityManagerTest extends KernelTestCase
{
    public function testEntityManager(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $entitymanager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->assertInstanceOf(EntityManagerInterface::class, $entitymanager);
        //$myCustomService = self::$container->get(CustomService::class);

        $factory = static::getContainer()->get(PostFactory::class);
        $this->assertInstanceOf(PostFactory::class, $factory);

        $post1 = $factory->create('Post title 01', 'Post Body 01');
        $post2 = $factory->create('Post title 02', 'Post Body 02');
        $post3 = $factory->create('Post title 03', 'Post Body 03');
        $post4 = $factory->create('Post title 04', 'Post Body 04');
        $entitymanager->persist($post1);
        $entitymanager->persist($post2);
        $entitymanager->persist($post3);
        $entitymanager->persist($post4);

        $entitymanager->flush();
    }

    public function testEntityManagerQuery():void
    {
        $kernel = self::bootKernel();

        $postRepo = static::getContainer()->get(PostRepository::class);

        $this->assertInstanceOf(PostRepository::class, $postRepo);

        $posts = $postRepo->findAll();
        $this->assertCount(4, $posts);
    }
}
