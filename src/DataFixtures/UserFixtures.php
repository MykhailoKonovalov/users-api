<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class UserFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $user
                ->setLogin('login_' . $i)
                ->setPhone(mt_rand(10000000, 99999999))
                ->setPass(substr(bin2hex(random_bytes(8)), 0, 8));

            $manager->persist($user);
        }

        $adminUser = new User();

        $adminUser
            ->setLogin('admin')
            ->setPhone('11010010')
            ->setPass('admin100');

        $manager->persist($adminUser);
        $manager->flush();
    }
}
