<?php

// src/DataFixtures/ScheduleFixture.php

namespace App\DataFixtures;

use App\Entity\Inspector;
use App\Entity\Job;
use App\Entity\Schedule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixture extends Fixture

{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // parent::__construct();
        $this->entityManager = $entityManager;
    }
    
    public function load(ObjectManager $manager)
    {
        // Create sample schedules
        for ($i = 0; $i < 4; $i++) {
            $schedule = new Schedule();
            $schedule->setAssignedAt(new \DateTime());
            
            // Get random Inspector and Job
            $inspector = $this->entityManager->getRepository(Inspector::class)->find(1);
            $job = $this->entityManager->getRepository(Job::class)->find(1);
            
            $schedule->setInspector($inspector);
            $schedule->setJob($job);

            // Persist the schedule
            $this->entityManager->persist($schedule);
        }

         // Flush changes to the database
         $manager->flush();
        }
    }
