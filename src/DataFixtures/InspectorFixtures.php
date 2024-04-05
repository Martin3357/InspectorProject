<?php

// src/DataFixtures/InspectorFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Inspector;

class InspectorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create and persist instances of Inspector entity with seed data
        $inspector1 = new Inspector();
        $inspector1->setName('John Doe');
        $manager->persist($inspector1);

        $inspector2 = new Inspector();
        $inspector2->setName('Jane Smith');
        $manager->persist($inspector2);

        // Flush the changes to the database
        $manager->flush();
    }
}
