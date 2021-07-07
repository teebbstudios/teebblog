<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    /**
     * @var PostFactory
     */
    private $postFactory;
    private $faker;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
        $this->faker=Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $post = $this->postFactory->create($this->faker->sentence(), $this->faker->paragraph());
            if ($this->faker->boolean()){
                $post->setStatus('published');
            }

            $image = '00'.$this->faker->randomDigit().'.jpg';
            $post->setPostImage($image);

            if ($i == 19){
                $post->setStatus('published');
            }

            $manager->persist($post);
        }

        $manager->flush();
    }


}
