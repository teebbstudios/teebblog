<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager)
    {
        $lastPost = $this->getReference(PostFixtures::LAST_POST);

        $commentArray = [];
        for ($i = 0; $i < 50; $i++) {
            $comment = new Comment();
            $comment->setAuthor($this->faker->name());
            $comment->setEmail($this->faker->email());
            $comment->setMessage($this->faker->paragraph());

            if ($i > 0 && $this->faker->boolean()){
                $parentComment = $this->faker->randomElement($commentArray);
                $comment->setParent($parentComment);
                $comment->setLevel($parentComment->getLevel() + 1);
            }else{
                $comment->setPost($lastPost);
            }

            $commentArray[] = $comment;

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PostFixtures::class
        ];
    }
}
