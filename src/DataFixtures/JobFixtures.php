<?php


namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JobFixtures extends Fixture
{
   
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $jobStatuses = ['In Progress', 'Completed', 'To Do'];

        for ($i = 0; $i < 10; $i++) {
            $job = new Job();
            $job->setDescription($faker->jobTitle);
            $job->setStatus($jobStatuses[array_rand($jobStatuses)]);
            $manager->persist($job);
        }

        // Flush all persisted objects
        $manager->flush();
    }
}

