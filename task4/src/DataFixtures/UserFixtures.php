<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, function() {
            $user = new User();
            $user->setName($this->faker->name());
            $user->setEmail($this->faker->email);

            return $user;
        });

        $manager->flush();
    }
}
