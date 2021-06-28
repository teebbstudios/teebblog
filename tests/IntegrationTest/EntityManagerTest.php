<?php

namespace App\Tests\IntegrationTest;

use App\Entity\Post;
use App\Factory\PostFactory;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityManagerTest extends KernelTestCase
{
    private $entitymanager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entitymanager = static::getContainer()->get('doctrine.orm.entity_manager');

        $this->truncateEntities([
            Post::class
        ]);
    }

    public function testEntityManager(): void
    {
//        $kernel = self::bootKernel();

        $this->assertSame('test', self::$kernel->getEnvironment());
//        $entitymanager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->assertInstanceOf(EntityManagerInterface::class, $this->entitymanager);
        //$myCustomService = self::$container->get(CustomService::class);

        $factory = static::getContainer()->get(PostFactory::class);
        $this->assertInstanceOf(PostFactory::class, $factory);

        $post1 = $factory->create('Post title 01', 'Post Body 01');
        $post2 = $factory->create('Post title 02', 'Post Body 02', null, 'published');
        $post3 = $factory->create('Post title 03', 'Post Body 03');
        $post4 = $factory->create('Post title 04', 'Post Body 04');
        $this->entitymanager->persist($post1);
        $this->entitymanager->persist($post2);
        $this->entitymanager->persist($post3);
        $this->entitymanager->persist($post4);

        $this->entitymanager->flush();

        $postRepo = static::getContainer()->get(PostRepository::class);

        $this->assertInstanceOf(PostRepository::class, $postRepo);

        $posts = $postRepo->findAll();
        $this->assertCount(4, $posts);

        $findOneByPost = $postRepo->findOneBy(['title' => 'Post title 01']);
        $this->assertSame($post1, $findOneByPost);

        $findPost = $postRepo->find($findOneByPost->getId());
        $this->assertSame($findOneByPost, $findPost);

        $findByPosts = $postRepo->findBy(['status' => 'draft']);
        $this->assertCount(3, $findByPosts);

    }

//    public function testEntityManagerQuery():void
//    {
////        $kernel = self::bootKernel();
//
//        $postRepo = static::getContainer()->get(PostRepository::class);
//
//        $this->assertInstanceOf(PostRepository::class, $postRepo);
//
//        $posts = $postRepo->findAll();
//        $this->assertCount(4, $posts);
//    }

    public function testQueryBuilder(): void
    {
        $factory = static::getContainer()->get(PostFactory::class);

        $post1 = $factory->create('Post title 01', 'Post Body 01');
        $post2 = $factory->create('Post title 02', 'Post Body 02', null, 'published');
        $post3 = $factory->create('Post title 03', 'Post Body 03');
        $post4 = $factory->create('Post title 04', 'Post Body 04');
        $this->entitymanager->persist($post1);
        $this->entitymanager->persist($post2);
        $this->entitymanager->persist($post3);
        $this->entitymanager->persist($post4);

        $this->entitymanager->flush();

        $qb = $this->entitymanager->createQueryBuilder();
        $posts = $qb->select('p')->from(Post::class, 'p')
            ->getQuery()->getResult();

        $this->assertCount(4, $posts);

        $postRepo = static::getContainer()->get(PostRepository::class);

        $posts = $postRepo->findByStatus('published');
        $this->assertCount(1, $posts);

        $findByTitlePosts = $postRepo->findByTitle('02');
        $this->assertCount(1, $findByTitlePosts);
        $this->assertSame('Post title 02', $findByTitlePosts[0]->getTitle());

        $findByTitlePostsDQL = $postRepo->findByTitleDQL('03');
        $this->assertCount(1, $findByTitlePostsDQL);
        $this->assertSame('Post title 03', $findByTitlePostsDQL[0]->getTitle());
    }

    private function truncateEntities(array $entities)
    {
        $connection = $this->entitymanager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $this->entitymanager->getClassMetadata($entity)->getTableName()
            );
            $connection->executeQuery($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
