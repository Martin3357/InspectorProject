<?php

// src/DataFixtures/JobSeeder.php
namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobSeeder extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create sample jobs
        $job1 = new Job();
        $job1->setDescription('Sample Job 1');
        $job1->setStatus('Pending');
        $manager->persist($job1);

        $job2 = new Job();
        $job2->setDescription('Sample Job 2');
        $job2->setStatus('Completed');
        $manager->persist($job2);

        

        $job3 = new Job();
        $job3->setDescription('Sample Job 2');
        $job3->setStatus('To Do');
        $manager->persist($job3);

        // Flush all persisted objects
        $manager->flush();
    }
}

