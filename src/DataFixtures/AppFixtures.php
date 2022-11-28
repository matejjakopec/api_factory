<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    protected $faker;

    protected $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }


    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++){
            $user = new User();
            $user->setUsername($this->faker->userName())
                ->setContractStartDate($this->faker->dateTimeBetween('-10 years', '+10 years'))
                ->setContractEndDate($this->faker->dateTimeBetween($user->getContractStartDate(), '+10 years'))
                ->setType($this->faker->randomElement(['normal', 'premium']))
                ->setVerified($this->faker->boolean())
                ->setPassword($this->passwordHasher->hashPassword($user, $this->faker->password()))
                ->setTokenValidAfter(new \DateTime());
            $manager->persist($user);
        }

        $manager->flush();
    }
}
