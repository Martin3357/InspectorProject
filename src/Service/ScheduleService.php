<?php
// src/Service/ScheduleService.php
namespace App\Service;

use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\InspectorService;
use App\Service\JobService;

class ScheduleService
{
    private $entityManager;
    private $inspectorService; // Add InspectorService property

    private $jobService;

    public function __construct(EntityManagerInterface $entityManager, InspectorService $inspectorService, JobService $jobService)
    {
        $this->entityManager = $entityManager;
        $this->inspectorService = $inspectorService; 
        $this->jobService = $jobService; 
    }

    public function getAllSchedules(): array
    {
        $schedules = $this->entityManager->getRepository(Schedule::class)->findAll();
        $responseData = [];
        foreach ($schedules as $schedule) {
            $responseData[] = $this->serializeSchedule($schedule);
        }
        return $responseData;
    }
    private function serializeSchedule(Schedule $schedule): array
    {
        $inspector = $this->inspectorService->getInspectorById($schedule->getInspector()->getId());
        $job = $this->jobService->getJobById($schedule->getJob()->getId());
        return [
            'id' => $schedule->getId(),
            'inspector' => $inspector,
            'job' => $job,
            // Add other properties as needed
        ];
    }

    public function getScheduleById(int $id): ?array
    {
        // return $this->entityManager->getRepository(Schedule::class)->find($id);
        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);
        if (!$schedule) {
            return null;
        }
        return $this->serializeSchedule($schedule);
    }

    public function createSchedule(array $data): Schedule
    {
        $schedule = new Schedule();
        // Populate $schedule with data from $data array
        // Example: $schedule->setInspector($data['inspector']);
        // Set other properties similarly
        
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        return $schedule;
    }

    public function updateSchedule(int $id, array $data): ?array
    {
        $schedule = $this->getScheduleById($id);
        // Update $schedule with data from $data array
        // Example: $schedule->setInspector($data['inspector']);
        // Set other properties similarly
        
        $this->entityManager->flush();

        return $schedule;
    }

    public function deleteSchedule(int $id): void
    {
        $schedule = $this->entityManager->getRepository(Schedule::class)->find($id);

        $this->entityManager->remove($schedule);
        $this->entityManager->flush();
    }
}

