<?php

// src/DataFixtures/InspectorFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Inspector;
use Faker\Factory;

class InspectorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        $faker = Factory::create();
        $inspectorLocation = ['Spain', 'Mexico', 'India'];

        for ($i = 0; $i < 10; $i++) {
            $inspector = new Inspector();
            $inspector->setName($faker->name);
            $inspector->setEmail($faker->email);
            $inspector->setPhoneNumber($faker->phoneNumber);
            $inspector->setLocation($inspectorLocation[array_rand($inspectorLocation)]);
            $manager->persist($inspector);
        }

        // Flush all persisted objects
        $manager->flush();
    }
}
