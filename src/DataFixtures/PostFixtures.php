<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    /**
     * @var PostFactory
     */
    private $postFactory;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $post = $this->postFactory->create('Fake Post Title ' . $i, 'Fake Post Body ' . $i);
            if ($i %2 ==0){
                $post->setStatus('published');
            }

            $manager->persist($post);
        }

        $manager->flush();
    }


}
