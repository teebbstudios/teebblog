<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ALLOWED_TO_SWITCH']);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'admin'));

        $deletedUser = new User();
        $deletedUser->setUsername('deleted');
        $deletedUser->setRoles(['ROLE_SUPER_ADMIN']);
        $deletedUser->setDeletedAt(new \DateTime('-1 day'));
        $deletedUser->setPassword($this->userPasswordHasher->hashPassword($deletedUser, '123'));

        $expiredUser = new User();
        $expiredUser->setUsername('expired');
        $expiredUser->setRoles(['ROLE_SUPER_ADMIN']);
        $expiredUser->setExpiredAt(new \DateTime('-1 day'));
        $expiredUser->setPassword($this->userPasswordHasher->hashPassword($expiredUser, '123'));

        $tom = new User();
        $tom->setUsername('tom');
        $tom->setPassword($this->userPasswordHasher->hashPassword($tom, 'tom'));


        $manager->persist($admin);
        $manager->persist($deletedUser);
        $manager->persist($expiredUser);
        $manager->persist($tom);

        $manager->flush();
    }
}
