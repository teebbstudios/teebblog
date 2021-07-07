<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    public const LAST_POST = 'last_post';
    /**
     * @var PostFactory
     */
    private $postFactory;
    private $faker;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $lastPost = null;
        for ($i = 0; $i < 20; $i++) {
            $post = $this->postFactory->create($this->faker->sentence(), $this->faker->paragraph());
            if ($this->faker->boolean()) {
                $post->setStatus('published');
            }

            $image = '00' . $this->faker->randomDigit() . '.jpg';
            $post->setPostImage($image);

            if ($i == 19) {
                $post->setStatus('published');
                $lastPost=$post;
            }

            $manager->persist($post);
        }

        $this->addReference(self::LAST_POST, $lastPost);

        $manager->flush();
    }


}
