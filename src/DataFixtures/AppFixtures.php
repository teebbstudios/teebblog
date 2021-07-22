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
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
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

        $editor = new User();
        $editor->setUsername('editor');
        $editor->setRoles(['ROLE_EDITOR']);
        $editor->setPassword($this->userPasswordHasher->hashPassword($editor, 'editor'));

        $checker = new User();
        $checker->setUsername('checker');
        $checker->setRoles(['ROLE_CHECKER']);
        $checker->setPassword($this->userPasswordHasher->hashPassword($checker, 'checker'));

        $simpleAdmin = new User();
        $simpleAdmin->setUsername('simple_admin');
        $simpleAdmin->setRoles(['ROLE_ADMIN']);
        $simpleAdmin->setPassword($this->userPasswordHasher->hashPassword($simpleAdmin, 'admin'));


        $manager->persist($admin);
        $manager->persist($deletedUser);
        $manager->persist($expiredUser);
        $manager->persist($tom);

        $manager->persist($editor);
        $manager->persist($checker);
        $manager->persist($simpleAdmin);

        $manager->flush();
    }
}
